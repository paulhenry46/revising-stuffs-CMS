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
     * @return array<int, array{title: string, level: int, page: int|null}>|null
     */
    public static function extractToc(ExecutableFinder $finder, string $sourcePath, int $postId): ?array
    {
        $mutoolPath = $finder->find('mutool');
        if (! $mutoolPath) {
            Log::warning('ExtractPdfExcerpt: mutool not found, TOC skipped for post '.$postId);

            return null;
        }

        $process = new Process([$mutoolPath, 'show', $sourcePath, 'outline']);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::warning('ExtractPdfExcerpt: mutool failed for post '.$postId.': '.$process->getErrorOutput());

            return null;
        }

        return self::parseTocOutline((string) $process->getOutput());
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
     * @return array<int, array{title: string, level: int, page: int|null}>
     */
    public static function parseTocOutline(string $outline): array
    {
        return collect(preg_split('/\R/u', $outline) ?: [])
            ->map(function (string $line): ?array {
                if (! preg_match('/"([^"]+)"/u', $line, $titleMatch, PREG_OFFSET_CAPTURE)) {
                    return null;
                }

                $title = trim($titleMatch[1][0]);
                if ($title === '') {
                    return null;
                }

                $quoteOffset = $titleMatch[0][1];
                $prefix = str_replace("\t", '        ', substr($line, 0, $quoteOffset));
                $level = max(1, intdiv(strlen($prefix) + 1, 8));

                $page = null;
                if (preg_match('/#page=(\d+)/i', $line, $pageMatch)) {
                    $page = (int) $pageMatch[1];
                }

                return [
                    'title' => $title,
                    'level' => $level,
                    'page' => $page,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
