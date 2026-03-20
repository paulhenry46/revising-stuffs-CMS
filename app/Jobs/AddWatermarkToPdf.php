<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class AddWatermarkToPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $fileId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = File::find($this->fileId);
        if (!$file || !$file->file_path) {
            return;
        }

        if (!in_array($file->type, ['primary light', 'primary dark'])) {
            return;
        }

        $sourcePath = Storage::disk('public')->path($file->file_path);
        if (!file_exists($sourcePath) || strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION)) !== 'pdf') {
            return;
        }

        $post = $file->post()->with('user')->first();
        if (!$post) {
            return;
        }

        $watermarkedPath = $this->generateWatermarked($sourcePath, $post, $file);
        if ($watermarkedPath) {
            $file->watermarked_file_path = $watermarkedPath;
            $file->save();
        }
    }

    /**
     * Generate a watermarked PDF and return its public-disk-relative path, or null on failure.
     */
    private function generateWatermarked(string $sourcePath, Post $post, File $file): ?string
    {
        $tmpDir = sys_get_temp_dir() . '/watermark_' . Str::random(10);
        mkdir($tmpDir, 0755, true);

        try {
            $safePdf = 'source.pdf';
            copy($sourcePath, $tmpDir . '/' . $safePdf);

            $latex = $this->buildLatex($post, $safePdf);
           
            file_put_contents($tmpDir . '/watermark.tex', $latex);

            $finder  = new ExecutableFinder();
            $pdflatexPath = $finder->find('pdflatex');
            if (!$pdflatexPath) {
                Log::warning('AddWatermarkToPdf: pdflatex not found, skipping watermark for file ' . $file->id);
                return null;
            }

            $process = new Process(
                [$pdflatexPath, '-interaction=nonstopmode', '-halt-on-error', 'watermark.tex'],
                $tmpDir
            );
            $process->run();

            $outputPdf = $tmpDir . '/watermark.pdf';
            if (!file_exists($outputPdf)) {
                $logPath = $tmpDir . '/watermark.log';
                $logTail = file_exists($logPath) ? substr(file_get_contents($logPath), -2000) : $process->getErrorOutput();
                Log::error('AddWatermarkToPdf: pdflatex failed for file ' . $file->id . ': ' . $logTail);
                return null;
            }

            // Build the destination path: same folder/name as original but with .watermarked before the extension
            $originalRelativePath = $file->file_path;
            $watermarkedRelativePath = $this->buildWatermarkedPath($originalRelativePath);

            $destinationPath = Storage::disk('public')->path($watermarkedRelativePath);
            Storage::disk('public')->makeDirectory(dirname($watermarkedRelativePath));
            copy($outputPdf, $destinationPath);

            return $watermarkedRelativePath;
        } finally {
            $this->cleanup($tmpDir);
        }
    }

    /**
     * Derive the watermarked file path from the original path.
     * e.g. "foo/bar/1-slug.light.pdf" => "foo/bar/1-slug.light.watermarked.pdf"
     */
    private function buildWatermarkedPath(string $originalPath): string
    {
        // Replace the last .pdf with .watermarked.pdf
        if (Str::endsWith($originalPath, '.pdf')) {
            return substr($originalPath, 0, -4) . '.watermarked.pdf';
        }
        return $originalPath . '.watermarked.pdf';
    }

    /**
     * Build the LaTeX source for the watermarked PDF.
     */
    private function buildLatex(Post $post, string $safePdf): string
    {
        $author      = $this->escape($post->user->name ?? '');
        $socialLink  = $this->escape($post->user->social_network_link ?? '');
        $license     = $this->escape($post->user->license ?? 'All rights reserved');
        $monthYear   = Carbon::parse($post->created_at)->translatedFormat('F Y');
        $monthYear   = $this->escape($monthYear);
        $postUrl     = $this->escape(route('post.short', $post->id));
        $safePdfPath = str_replace('\\', '/', $safePdf);

        $socialBlock = '';
        if ($post->user->social_network_link) {
            $socialBlock = ' \\textbf{(' . $socialLink . ')}';
        }

        return <<<LATEX
\\documentclass{article}
\\usepackage[utf8]{inputenc}
\\usepackage[T1]{fontenc}
\\usepackage{pdfpages}
\\usepackage{graphicx}
\\usepackage{calc}
\\usepackage{eso-pic}
\\usepackage{xcolor}
\\usepackage{lmodern}

\\newlength{\\largeurBandeau}
\\setlength{\\largeurBandeau}{3cm}

\\AddToShipoutPictureBG{%
  \\put(0,0){%
    \\colorbox{gray!10}{%
      \\begin{minipage}[t][\\paperheight][t]{\\largeurBandeau}
        \\vspace{1cm}
        \\centering
        \\rotatebox{90}{%
          \\small\\textsf{\\textbf{{$author}}$socialBlock --- $license --- $monthYear}%
        }
        \\vfill
        \\rotatebox{90}{\\scriptsize\\textsf{An error? Report it on $postUrl}}
        \\vspace{1cm}
      \\end{minipage}%
    }%
  }%
}

\\begin{document}
\\includepdf[pages=-,fitpaper,margin=\\largeurBandeau{} 0 0 0]{{$safePdfPath}}
\\end{document}
LATEX;
    }

    /**
     * Escape special LaTeX characters.
     */
    private function escape(string $text): string
    {
        return strtr($text, [
            '\\' => '\\textbackslash{}',
            '{' => '\\{',
            '}' => '\\}',
            '&' => '\\&',
            '%' => '\\%',
            '$' => '\\$',
            '#' => '\\#',
            '_' => '\\_',
            '~' => '\\textasciitilde{}',
            '^' => '\\textasciicircum{}',
        ]);
    }

    /**
     * Recursively delete a temporary directory.
     */
    private function cleanup(string $tmpDir): void
    {
        if (!is_dir($tmpDir) || !Str::startsWith($tmpDir, sys_get_temp_dir())) {
            return;
        }
        foreach (glob($tmpDir . '/*') as $item) {
            if (is_dir($item)) {
                $this->cleanup($item);
            } elseif (is_writable($item)) {
                unlink($item);
            }
        }
        if (is_writable($tmpDir)) {
            rmdir($tmpDir);
        }
    }
}
