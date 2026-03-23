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

            $latex = $this->buildLatex($post, $safePdf, $pdfInfo['page_dimensions']);
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
     * Get per-page PDF dimensions (in mm) and page count using pdfinfo.
     * Falls back to A4 defaults if pdfinfo is unavailable or fails.
     *
     * @return array{pages: int, page_dimensions: array<int, array{0: float, 1: float}>}
     */
    private function getPdfInfo(string $pdfPath): array
    {
        $a4 = [210.0, 297.0];
        $defaults = ['pages' => 1, 'page_dimensions' => [1 => $a4]];

        $finder = new ExecutableFinder();
        $pdfInfoPath = $finder->find('pdfinfo');
        if (!$pdfInfoPath) {
            Log::info('AddWatermarkToPdf: pdfinfo not found, using A4 defaults.');
            return $defaults;
        }

        // Get total page count and fallback dimensions from the global pdfinfo output
        $process = new Process([$pdfInfoPath, $pdfPath]);
        $process->run();
        if (!$process->isSuccessful()) {
            return $defaults;
        }

        $output = $process->getOutput();

        $pages = 1;
        if (preg_match('/Pages:\s+(\d+)/i', $output, $m)) {
            $pages = max(1, (int) $m[1]);
        }

        // Fallback dimensions from the global "Page size:" line (usually page 1)
        $fallback = $a4;
        if (preg_match('/Page size:\s+([\d.]+)\s+x\s+([\d.]+)\s+pts/i', $output, $m)) {
            $fallback = [
                round(floatval($m[1]) * 25.4 / 72, 2),
                round(floatval($m[2]) * 25.4 / 72, 2),
            ];
        }

        // Get per-page dimensions using pdfinfo -f 1 -l <pages>
        $perPageProcess = new Process([$pdfInfoPath, '-f', '1', '-l', (string) $pages, $pdfPath]);
        $perPageProcess->run();

        $pageDimensions = [];
        if ($perPageProcess->isSuccessful()) {
            $perPageOutput = $perPageProcess->getOutput();
            // Output contains lines like: "Page    1 size:      595.28 x 841.89 pts"
            preg_match_all('/Page\s+(\d+)\s+size:\s+([\d.]+)\s+x\s+([\d.]+)\s+pts/i', $perPageOutput, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $pageNum = (int) $match[1];
                $pageDimensions[$pageNum] = [
                    round(floatval($match[2]) * 25.4 / 72, 2),
                    round(floatval($match[3]) * 25.4 / 72, 2),
                ];
            }
        }

        // Fill in the fallback for any page whose dimensions were not reported
        for ($i = 1; $i <= $pages; $i++) {
            if (!isset($pageDimensions[$i])) {
                $pageDimensions[$i] = $fallback;
            }
        }

        ksort($pageDimensions);

        return ['pages' => $pages, 'page_dimensions' => $pageDimensions];
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
     * Build the LaTeX source for the watermarked PDF using per-page dimensions.
     *
     * @param  array<int, array{0: float, 1: float}>  $pageDimensions  Per-page [widthMm, heightMm]
     */
    private function buildLatex(Post $post, string $safePdf, array $pageDimensions): string
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

    $bannerWidth = 20; // Réduit à 20mm pour plus d'élégance
    $logoPath = str_replace('\\', '/', base_path('resources/png/logo.pdf'));
    $written = __('Written By');
    $host = $this->escape(parse_url(config('app.url'), PHP_URL_HOST) ?? '');

    $versionNumber = \DB::table('events')
        ->where('post_id', $post->id)
        ->count() + 1;

    // Use first page dimensions only for the initial \geometry declaration so
    // LaTeX has a valid paper size before the first page is shipped out.
    $firstPage      = $pageDimensions[array_key_first($pageDimensions)];
    $firstTotalWidth = round($firstPage[0] + $bannerWidth, 2);
    $firstHeight     = $firstPage[1];

    // --- GENERATE PER-PAGE CONTENT ---
    // Each page may have different width AND height, so we clear and rebuild the
    // eso-pic overlays for every page using that page's own dimensions.
    $pageContent = '';
    foreach ($pageDimensions as $pageNum => [$pageWidth, $pageHeight]) {
        $pageTotalWidth   = round($pageWidth + $bannerWidth, 2);
        $pageIncludeWidth = round($pageWidth - 1, 2);
        $midHeight        = round($pageHeight / 2, 2);
        $logo_height      = $pageHeight - 18;
        $logo_line_height = $logo_height - 10;

        $pageContent .= "\\pdfpagewidth={$pageTotalWidth}mm\n";
        $pageContent .= "\\pdfpageheight={$pageHeight}mm\n";
        $pageContent .= "\\ClearShipoutPictureBG\n";
        $pageContent .= "\\AddToShipoutPictureBG{%\n";
        $pageContent .= "  \\AtPageLowerLeft{%\n";
        $pageContent .= "  \\color{black}\\hspace{{$bannerWidth}mm}\\rule{0.2pt}{{$pageHeight}mm}%\n";
        $pageContent .= "  }%\n";
        $pageContent .= "}\n";
        $pageContent .= "\\ClearShipoutPictureFG\n";
        $pageContent .= "\\AddToShipoutPictureFG{%\n";
        $pageContent .= "  \\AtPageLowerLeft{%\n";
        $pageContent .= "  \\put(2mm,2mm){\\color{gray!40}\\rule{3mm}{0.2pt}}%\n";
        $pageContent .= "    \\put(2mm,2mm){\\color{gray!40}\\rule{0.2pt}{3mm}}%\n";
        $pageContent .= "    \\put(15mm,78mm){\\color{gray!40}\\rule{3mm}{0.2pt}}%\n";
        $pageContent .= "    \\put(18mm,75mm){\\color{gray!40}\\rule{0.2pt}{3mm}}%\n";
        $pageContent .= "    \\put(5mm,{$logo_height}mm){\\includegraphics[height=8mm]{{$logoPath}}}%\n";
        $pageContent .= "    \\put(10mm,10mm){\\rotatebox{90}{\\fontsize{7}{8}\\selectfont\\ttfamily\\color{black} \\MakeUppercase{{$host}} \$\\boldsymbol{\\cdot}\$ \\MakeUppercase{{$course}} \$\\boldsymbol{\\cdot}\$ \\MakeUppercase{{$level}}}}%\n";
        $pageContent .= "    \\put(6.5mm,{$midHeight}mm){\\rotatebox{90}{\\fontsize{10}{12}\\selectfont\\sffamily {$written} \\textbf{\\MakeUppercase{{$author}}} {$iconCode}}}%\n";
        $pageContent .= "    \\put(11.5mm,{$midHeight}mm){\\rotatebox{90}{\\fontsize{7}{9}\\selectfont\\sffamily {$school} \$\\boldsymbol{\\cdot}\$ {$user_level}}}%\n";
        $pageContent .= "    \\put(0mm,80mm){\\color{black}\\rule{20mm}{0.1pt}}\n";
        $pageContent .= "    \\put(0mm,{$logo_line_height}mm){\\color{black}\\rule{20mm}{0.1pt}}\n";
        $pageContent .= "    \\put(4mm,10mm){\\rotatebox{90}{\\fontsize{7}{9}\\selectfont\\ttfamily\\color{black} VER: 0{$versionNumber} \$\\boldsymbol{\\cdot}\$ ID: {$post->id} \$\\boldsymbol{\\cdot}\$ LICENSE\\raisebox{-0.25\\height}{ {$licenseIcons} }}}%\n";
        $pageContent .= "    \\put(15mm,10mm){\\rotatebox{90}{\\fontsize{6}{8}\\selectfont\\sffamily\\color{black} \\href{{$postUrl}}{{$errorMsg} {$postUrl}}}}%\n";
        $pageContent .= "  }%\n";
        $pageContent .= "}\n";
        $pageContent .= "\\noindent\\hspace*{{$bannerWidth}mm}%\n";
        $pageContent .= "  \\includegraphics[page={$pageNum},width={$pageIncludeWidth}mm]{{$safePdfPath}}%\n";
        $pageContent .= "\\clearpage\n";
    }

    return <<<LATEX
\\documentclass{article}
\\usepackage[utf8]{inputenc}
\\usepackage[T1]{fontenc}
\\usepackage{graphicx}
\\usepackage{eso-pic}
\\usepackage{xcolor}
\\usepackage{geometry}
\\usepackage{lmodern}
\\usepackage{amsmath}
\\usepackage[sfdefault]{FiraSans}
\\usepackage[hidelinks]{hyperref}

\\geometry{
  paperwidth={$firstTotalWidth}mm,
  paperheight={$firstHeight}mm,
  left=0mm, right=0mm, top=0mm, bottom=0mm
}

\\begin{document}

% --- RENDER (per-page dimensions and banner) ---
$pageContent
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
