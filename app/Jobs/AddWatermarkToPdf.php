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
use Illuminate\Support\Facades\DB;
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
        if(!config('features.latex_enabled')){
            return ;
        }
        
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

            $latex = $this->buildLatex($post, $safePdf, $pdfInfo['width'], $pdfInfo['height']);
           // dd($latex);
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

            $finalPdf = $outputPdf;
            if (($pdfInfo['pages'] ?? 1) > 1) {
                $mergedPdf = $this->mergeFirstPageWithRemaining($tmpDir, $outputPdf, $tmpDir . '/' . $safePdf, (int) $pdfInfo['pages'], $file->id);
                if (!$mergedPdf) {
                    return null;
                }
                $finalPdf = $mergedPdf;
            }

            // Build the destination path: same folder/name as original but with .watermarked before the extension
            $originalRelativePath = $file->file_path;
            $watermarkedRelativePath = $this->buildWatermarkedPath($originalRelativePath);

            $destinationPath = Storage::disk('public')->path($watermarkedRelativePath);
            Storage::disk('public')->makeDirectory(dirname($watermarkedRelativePath));
            copy($finalPdf, $destinationPath);

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
     */
    private function buildLatex(Post $post, string $safePdf, float $widthMm, float $heightMm): string
    {
    // --- PRÉPARATION DES DONNÉES ---
    $author     = $this->escape($post->user->name ?? 'Anonyme');
    $license    = $this->escape($post->user->license ?? 'CC BY-SA');
    $monthYear  = $this->escape(Carbon::parse($post->created_at)->translatedFormat('F Y'));
    $postUrl    = $this->escape(route('post.short', $post->id));
    $course   = $this->escape($post->course->name ?? 'Course');
    $level      = $this->escape($post->level->name ?? 'Level');
    $safePdfPath = str_replace('\\', '/', $safePdf);
    $errorMsg   = $this->escape(__('An error? Report it on'));
    $school = $this->escape($post->user->school->name ?? '');
    $user_level = $this->escape($post->user->level->name ?? '');

    // --- LOGIQUE DE L'ICÔNE SOCIALE ---
    $iconCode = "";
    $socialLinkRaw = $post->user->social_network_link;
    if (!empty($socialLinkRaw)) {
        $iconName = 'web.png';
        if (str_contains($socialLinkRaw, 'github.com'))    $iconName = 'gh.png';
        elseif (str_contains($socialLinkRaw, 'tiktok.com'))   $iconName = 'tt.png';
        elseif (str_contains($socialLinkRaw, 'instagram.com'))$iconName = 'inst.png';
        elseif (str_contains($socialLinkRaw, 'discord'))       $iconName = 'd.png';

        $iconPath = str_replace('\\', '/', base_path('resources/png/' . $iconName));
        $iconCode = "\\raisebox{-0.25\height}{ \\href{{$socialLinkRaw}}{\\includegraphics[width=4mm]{{$iconPath}}} }";
    }

    // --- LOGIQUE DES ICÔNES DE LICENCE ---
    $resPath = str_replace('\\', '/', base_path('resources/png/'));
    $licenseIcons = "\\includegraphics[height=3.5mm]{{$resPath}CC.png}\\hspace{0.3mm}";
    if (str_contains($license, 'BY')) $licenseIcons .= "\\includegraphics[height=3.5mm]{{$resPath}BY.png}\\hspace{0.3mm}";
    if (str_contains($license, 'SA')) $licenseIcons .= "\\includegraphics[height=3.5mm]{{$resPath}SA.png}\\hspace{0.3mm}";
    if (str_contains($license, '0'))  $licenseIcons .= "\\includegraphics[height=3.5mm]{{$resPath}0.png}\\hspace{0.3mm}";

    // --- DIMENSIONS ---
    $bannerWidth = 20; // Réduit à 20mm pour plus d'élégance
    $totalWidth  = round($widthMm + $bannerWidth, 2);
    $widthMm2    = $widthMm - 1; 
    $midHeight   = round($heightMm / 2, 2);
    $topPos      = $heightMm - 10; // 10mm du bord haut
    $written = __('Written By');

    $logo_height = $heightMm - 18;
    $logo_line_height = $logo_height - 10;
    $logoPath = str_replace('\\', '/', base_path('resources/png/logo.pdf')); 
    
    $versionNumber = DB::table('events')
        ->where('post_id', $post->id)
        ->count() + 1;

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
\\usepackage{amsmath}
\\usepackage[sfdefault]{FiraSans}
\\usepackage[hidelinks]{hyperref}

\\geometry{
  paperwidth={$totalWidth}mm,
  paperheight={$heightMm}mm,
  left=0mm, right=0mm, top=0mm, bottom=0mm
}

\\begin{document}

% --- BANNER BACKGROUND & LINE ---
\\AddToShipoutPictureBG*{%
  \\AtPageLowerLeft{%
  \\color{black}\\hspace{{$bannerWidth}mm}\\rule{0.2pt}{{$heightMm}mm}%
   % \\color{gray!5}\\rule{{$bannerWidth}mm}{{$heightMm}mm}%
  % \\color{gray!2}\\rule{{$bannerWidth}mm}{{$heightMm}mm}%
    
  }%
}

% --- BANNER CONTENT (ARCHIVE STYLE) ---
\\AddToShipoutPictureFG*{%
  \\AtPageLowerLeft{%
  \\put(2mm,2mm){\\color{gray!40}\\rule{3mm}{0.2pt}}% Horizontal
    \\put(2mm,2mm){\\color{gray!40}\\rule{0.2pt}{3mm}}% Vertical

    \\put(15mm,78mm){\\color{gray!40}\\rule{3mm}{0.2pt}}% Horizontal
    \\put(18mm,75mm){\\color{gray!40}\\rule{0.2pt}{3mm}}% Vertical
    
    \\put(5mm,{$logo_height}mm){\\includegraphics[height=8mm]{{$logoPath}}}%
    
    % 1. TOP : CLASSIFICATION & SOURCE
    \\put(10mm,10mm){\\rotatebox{90}{
        \\fontsize{7}{8}\\selectfont\\ttfamily\\color{black} 
        \\MakeUppercase{{$this->escape(parse_url(config('app.url'), PHP_URL_HOST)) }} $\boldsymbol{\cdot}$ \\MakeUppercase{{$course}} $\boldsymbol{\cdot}$ \\MakeUppercase{{$level}}
    }}%

    % 2. MIDDLE : AUTHOR & SOCIAL
    \\put(6.5mm,{$midHeight}mm){\\rotatebox{90}{
        \\fontsize{10}{12}\\selectfont\\sffamily $written
        \\textbf{\\MakeUppercase{{$author}}} {$iconCode}
    }}%

    \\put(11.5mm,{$midHeight}mm){\\rotatebox{90}{
        \\fontsize{7}{9}\\selectfont\\sffamily
        $school $\boldsymbol{\cdot}$ $user_level
    }}%

    \\put(0mm,80mm){\\color{black}\\rule{20mm}{0.1pt}}

    \\put(0mm,$logo_line_height mm){\\color{black}\\rule{20mm}{0.1pt}}

    % 3. BOTTOM : TECH INFO & LICENSE
    \\put(4mm,10mm){\\rotatebox{90}{
        \\fontsize{7}{9}\\selectfont\\ttfamily\\color{black} 

        VER: 0$versionNumber $\boldsymbol{\cdot}$ ID: {$post->id} $\boldsymbol{\cdot}$ LICENSE\\raisebox{-0.25\height}{ {$licenseIcons} }
    }}%

    % 4. BOTTOM : LINK & ERROR REPORT
    \\put(15mm,10mm){\\rotatebox{90}{
        \\fontsize{6}{8}\\selectfont\\sffamily\\color{black}
        \\href{{$postUrl}}{{$errorMsg} {$postUrl}}
    }}%

  }%
}

% --- RENDER FIRST PAGE (WITH WATERMARK) ---
\\pdfpagewidth={$totalWidth}mm
\\pdfpageheight={$heightMm}mm
\\noindent\\hspace*{{$bannerWidth}mm}%
\\includegraphics[page=1,width={$widthMm2}mm]{{$safePdfPath}}%

\\end{document}
LATEX;
}

    /**
     * Merge the generated first-page watermark PDF with pages 2..N from the original PDF.
     */
    private function mergeFirstPageWithRemaining(string $tmpDir, string $firstPagePdf, string $sourcePdf, int $pages, int $fileId): ?string
    {
        if ($pages <= 1) {
            return $firstPagePdf;
        }

        $mergedPdf = $tmpDir . '/watermark-merged.pdf';
        $finder = new ExecutableFinder();

        $qpdfPath = $finder->find('qpdf');
        if ($qpdfPath) {
            $process = new Process([
                $qpdfPath,
                '--empty',
                '--pages',
                $firstPagePdf,
                '1',
                $sourcePdf,
                '2-z',
                '--',
                $mergedPdf,
            ]);
            $process->run();

            if ($process->isSuccessful() && file_exists($mergedPdf)) {
                return $mergedPdf;
            }

            Log::warning('AddWatermarkToPdf: qpdf merge failed for file ' . $fileId . ': ' . $process->getErrorOutput());
        }

        $pdfSeparatePath = $finder->find('pdfseparate');
        $pdfUnitePath = $finder->find('pdfunite');
        if ($pdfSeparatePath && $pdfUnitePath) {
            $splitPattern = $tmpDir . '/source-page-%d.pdf';
            $splitProcess = new Process([
                $pdfSeparatePath,
                '-f',
                '2',
                '-l',
                (string) $pages,
                $sourcePdf,
                $splitPattern,
            ]);
            $splitProcess->run();

            if ($splitProcess->isSuccessful()) {
                $remainingPages = glob($tmpDir . '/source-page-*.pdf') ?: [];
                natsort($remainingPages);
                $remainingPages = array_values($remainingPages);

                if (!empty($remainingPages)) {
                    $mergeArgs = array_merge([$pdfUnitePath, $firstPagePdf], $remainingPages, [$mergedPdf]);
                    $mergeProcess = new Process($mergeArgs);
                    $mergeProcess->run();

                    if ($mergeProcess->isSuccessful() && file_exists($mergedPdf)) {
                        return $mergedPdf;
                    }

                    Log::warning('AddWatermarkToPdf: pdfunite merge failed for file ' . $fileId . ': ' . $mergeProcess->getErrorOutput());
                }
            } else {
                Log::warning('AddWatermarkToPdf: pdfseparate split failed for file ' . $fileId . ': ' . $splitProcess->getErrorOutput());
            }
        }

        Log::error('AddWatermarkToPdf: no working PDF merge strategy found for file ' . $fileId . ' (tried qpdf and pdfseparate/pdfunite).');
        return null;
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
