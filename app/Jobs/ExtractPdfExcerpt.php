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
        $pdfToTextPath = $finder->find('pdftotext');
        if (! $pdfToTextPath) {
            Log::warning('ExtractPdfExcerpt: pdftotext not found, skipping post '.$this->postId);

            return;
        }

        $process = new Process([$pdfToTextPath, '-nopgbrk', $sourcePath, '-']);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::warning('ExtractPdfExcerpt: pdftotext failed for post '.$this->postId.': '.$process->getErrorOutput());

            return;
        }

        $text = self::sanitizeText((string) $process->getOutput());
        if ($text === '') {
            PdfExcerpt::updateOrCreate(
                ['post_id' => $this->postId],
                ['excerpt' => null]
            );

            return;
        }

        $splitLimit = self::WORD_LIMIT + 1;
        $words = preg_split('/\s+/u', $text, $splitLimit, PREG_SPLIT_NO_EMPTY) ?: [];
        $excerpt = implode(' ', array_slice($words, 0, self::WORD_LIMIT));

        PdfExcerpt::updateOrCreate(
            ['post_id' => $this->postId],
            ['excerpt' => $excerpt]
        );
    }

    public static function sanitizeText(string $text): string
    {
        $text = preg_replace('/[^\p{Latin}\p{N}\s]/u', ' ', $text) ?? '';

        return preg_replace('/\s+/u', ' ', trim($text)) ?? '';
    }
}
