<?php

namespace App\Jobs;

use App\Models\PdfExcerpt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class ExtractPdfExcerpt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const WORD_LIMIT = 200;

    protected int $postId;

    protected string $pdfPath;

    /**
     * Create a new job instance.
     */
    public function __construct(int $postId, string $pdfPath)
    {
        $this->postId = $postId;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sourcePath = Storage::disk('public')->path($this->pdfPath);
        if (! file_exists($sourcePath) || strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION)) !== 'pdf') {
            return;
        }

        $finder = new ExecutableFinder;
        $excerpt = self::extractExcerpt($finder, $sourcePath, $this->postId);
        $toc = self::extractToc($finder, $sourcePath, $this->postId);

        PdfExcerpt::updateOrCreate(
            ['post_id' => $this->postId],
            [
                'excerpt' => $excerpt,
                'toc' => $toc,
            ]
        );
    }

    public static function sanitizeText(string $text): string
    {
        $text = preg_replace('/[^\p{Latin}\p{N}\s]/u', ' ', $text) ?? '';

        return preg_replace('/\s+/u', ' ', trim($text)) ?? '';
    }

    /**
     * @return array<int, array{title: string, level: int, page: int}>|null
     */
    public static function extractToc(ExecutableFinder $finder, string $sourcePath, int $postId): ?array
    {
        $pdfTkPath = $finder->find('pdftk');
        if (! $pdfTkPath) {
            Log::warning('ExtractPdfExcerpt: pdftk not found, TOC skipped for post '.$postId);

            return null;
        }

        $process = new Process([$pdfTkPath, $sourcePath, 'dump_data']);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::warning('ExtractPdfExcerpt: pdftk failed for post '.$postId.': '.$process->getErrorOutput());

            return null;
        }

        return self::parseTocDumpData((string) $process->getOutput());
    }

    public static function extractExcerpt(ExecutableFinder $finder, string $sourcePath, int $postId): ?string
    {
        $pdfToTextPath = $finder->find('pdftotext');
        if (! $pdfToTextPath) {
            Log::warning('ExtractPdfExcerpt: pdftotext not found, excerpt skipped for post '.$postId);

            return null;
        }

        $process = new Process([$pdfToTextPath, '-nopgbrk', $sourcePath, '-']);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::warning('ExtractPdfExcerpt: pdftotext failed for post '.$postId.': '.$process->getErrorOutput());

            return null;
        }

        $text = self::sanitizeText((string) $process->getOutput());
        if ($text === '') {
            return null;
        }

        $splitLimit = self::WORD_LIMIT + 1;
        $words = preg_split('/\s+/u', $text, $splitLimit, PREG_SPLIT_NO_EMPTY) ?: [];

        return implode(' ', array_slice($words, 0, self::WORD_LIMIT));
    }

    /**
     * @return array<int, array{title: string, level: int, page: int}>
     */
    public static function parseTocDumpData(string $dumpData): array
    {
        preg_match_all('/BookmarkBegin\s+BookmarkTitle:\s*(.+?)\s+BookmarkLevel:\s*(\d+)\s+BookmarkPageNumber:\s*(\d+)/su', $dumpData, $matches, PREG_SET_ORDER);

        return collect($matches)->map(function (array $match) {
            return [
                'title' => trim($match[1]),
                'level' => (int) $match[2],
                'page' => (int) $match[3],
            ];
        })->values()->all();
    }
}
