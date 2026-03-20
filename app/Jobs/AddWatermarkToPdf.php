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

            // Detect page dimensions and page count from the source PDF
            $pdfInfo = $this->getPdfInfo($tmpDir . '/' . $safePdf);

            $latex = $this->buildLatex($post, $safePdf, $pdfInfo['width'], $pdfInfo['height'], $pdfInfo['pages']);
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
     * Get PDF dimensions (in mm) and page count using pdfinfo.
     * Falls back to A4 defaults if pdfinfo is unavailable or fails.
     *
     * @return array{width: float, height: float, pages: int}
     */
    private function getPdfInfo(string $pdfPath): array
    {
        $defaults = ['width' => 210.0, 'height' => 297.0, 'pages' => 1];

        $finder = new ExecutableFinder();
        $pdfInfoPath = $finder->find('pdfinfo');
        if (!$pdfInfoPath) {
            Log::info('AddWatermarkToPdf: pdfinfo not found, using A4 defaults.');
            return $defaults;
        }

        $process = new Process([$pdfInfoPath, $pdfPath]);
        $process->run();
        if (!$process->isSuccessful()) {
            return $defaults;
        }

        $output = $process->getOutput();

        $width  = $defaults['width'];
        $height = $defaults['height'];
        $pages  = $defaults['pages'];

        // Page size is given in points: "Page size:      595.28 x 841.89 pts (A4)"
        if (preg_match('/Page size:\s+([\d.]+)\s+x\s+([\d.]+)\s+pts/i', $output, $m)) {
            $width  = round(floatval($m[1]) * 25.4 / 72, 2);
            $height = round(floatval($m[2]) * 25.4 / 72, 2);
        }

        if (preg_match('/Pages:\s+(\d+)/i', $output, $m)) {
            $pages = (int) $m[1];
        }

        return ['width' => $width, 'height' => $height, 'pages' => $pages];
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
     * Build the LaTeX source for the watermarked PDF using dynamic page dimensions.
     *
     * @param  float  $widthMm   PDF page width in mm
     * @param  float  $heightMm  PDF page height in mm
     * @param  int    $pages     Total number of pages in the PDF
     */
    private function buildLatex(Post $post, string $safePdf, float $widthMm, float $heightMm, int $pages): string
    {
        $author     = $this->escape($post->user->name ?? '');
        $socialLink = $this->escape($post->user->social_network_link ?? '');
        $license    = $this->escape($post->user->license ?? 'All rights reserved');
        $monthYear  = $this->escape(Carbon::parse($post->created_at)->translatedFormat('F Y'));
        $postUrl    = $this->escape(route('post.short', $post->id));
        $safePdfPath = str_replace('\\', '/', $safePdf);

        // Build the author line for the top of the banner
        $authorLine = "\\textbf{{$author}}";
        if ($post->user->social_network_link) {
            $authorLine .= " ({$socialLink})";
        }
        $authorLine .= " --- {$license} --- {$monthYear}";

        // The total paper width = original PDF width + 30 mm banner
        $bannerWidth   = 30;
        $totalWidth    = round($widthMm + $bannerWidth, 2);
        $midHeight     = round($heightMm / 2, 2);

        // Build the \foreach page list: {1,...,N}
        $pageList = $pages > 1 ? "1,...,{$pages}" : '1';

        return <<<LATEX
\\documentclass{article}
\\usepackage[utf8]{inputenc}
\\usepackage[T1]{fontenc}
\\usepackage{graphicx}
\\usepackage{eso-pic}
\\usepackage{xcolor}
\\usepackage{geometry}
\\usepackage{pgffor}
\\usepackage{lmodern}

% Paper enlarged: PDF width + 30mm banner
\\geometry{
  paperwidth={$totalWidth}mm,
  paperheight={$heightMm}mm,
  left=0mm, right=0mm, top=0mm, bottom=0mm
}

\\begin{document}

% --- BANNER BACKGROUND ---
\\AddToShipoutPictureBG{%
  \\AtPageLowerLeft{%
    \\color{gray!10}\\rule{{$bannerWidth}mm}{{$heightMm}mm}%
  }%
}

% --- BANNER TEXT ---
\\AddToShipoutPictureFG{%
  \\AtPageLowerLeft{%
    \\put(5mm,{$midHeight}mm){\\rotatebox{90}{\\small\\textsf{{$authorLine}}}}%
    \\put(10mm,{$midHeight}mm){\\rotatebox{90}{\\scriptsize\\textsf{An error? Report it on {$postUrl}}}}%
  }%
}

% --- RENDER ALL PAGES OF THE PDF ---
\\foreach \\p in {{$pageList}}{%
  \\noindent\\hspace*{{$bannerWidth}mm}%
  \\includegraphics[page=\\p,width={$widthMm}mm]{{$safePdfPath}}%
  \\clearpage
}

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
