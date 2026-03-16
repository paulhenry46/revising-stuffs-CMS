<?php

namespace App\Services;

use App\Http\Controllers\CurriculumController;
use App\Models\Curriculum;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LatexPackService
{
    /**
     * Generate a PDF pack from the given posts and return the path to the PDF file.
     * The caller is responsible for cleaning up the returned temp directory.
     *
     * @param  string      $packTitle
     * @param  Post[]      $posts       Ordered array of Post models (with user and files loaded)
     * @param  Curriculum  $curriculum
     * @return array{pdfPath: string, tmpDir: string}
     *
     * @throws \RuntimeException if pdflatex fails
     */
    public function generate(string $packTitle, array $posts, Curriculum $curriculum): array
    {
        $tmpDir = sys_get_temp_dir() . '/latex_pack_' . Str::random(10);
        mkdir($tmpDir, 0755, true);

        // Copy logo to temp dir (if exists)
        $logoFile = null;
        $logoPath = CurriculumController::logoPath($curriculum);
        if ($logoPath) {
            $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $logoFile = 'logo.' . $ext;
            copy($logoPath, $tmpDir . '/' . $logoFile);
        }

        // Copy primary PDF files for each post to temp dir with safe names
        $postPdfs = [];
        foreach ($posts as $index => $post) {
            $primaryFile = $post->files->first(fn($f) => Str::startsWith($f->type ?? '', 'primary'));
            if ($primaryFile && $primaryFile->file_path) {
                $sourcePath = Storage::disk('public')->path($primaryFile->file_path);
                if (file_exists($sourcePath) && strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION)) === 'pdf') {
                    $safeName = 'post_' . ($index + 1) . '.pdf';
                    copy($sourcePath, $tmpDir . '/' . $safeName);
                    $postPdfs[$index] = $safeName;
                }
            }
        }

        // Build and write the .tex file
        $latex = $this->buildLatex($packTitle, $posts, $curriculum, $logoFile, $postPdfs);
        file_put_contents($tmpDir . '/pack.tex', $latex);

        // Run pdflatex twice so that \pageref{LastPage} is resolved correctly
        $cmd = sprintf(
            'cd %s && pdflatex -interaction=nonstopmode -halt-on-error pack.tex > /dev/null 2>&1 && '
            . 'pdflatex -interaction=nonstopmode -halt-on-error pack.tex > /dev/null 2>&1',
            escapeshellarg($tmpDir)
        );
        exec($cmd, $output, $returnCode);

        $pdfFile = $tmpDir . '/pack.pdf';
        if (!file_exists($pdfFile)) {
            // Try to get log for diagnostics
            $log = file_exists($tmpDir . '/pack.log') ? file_get_contents($tmpDir . '/pack.log') : 'No log available.';
            throw new \RuntimeException('pdflatex failed. Log: ' . substr($log, -2000));
        }

        return ['pdfPath' => $pdfFile, 'tmpDir' => $tmpDir];
    }

    /**
     * Recursively delete a temp directory created by generate().
     */
    public function cleanup(string $tmpDir): void
    {
        if (!is_dir($tmpDir) || !Str::startsWith($tmpDir, sys_get_temp_dir())) {
            return;
        }
        foreach (glob($tmpDir . '/*') as $file) {
            if (is_dir($file)) {
                $this->cleanup($file);
            } elseif (is_writable($file)) {
                unlink($file);
            }
        }
        if (is_writable($tmpDir)) {
            rmdir($tmpDir);
        }
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function buildLatex(
        string $packTitle,
        array $posts,
        Curriculum $curriculum,
        ?string $logoFile,
        array $postPdfs
    ): string {
        $instanceName = $curriculum->app_name ?: (env('INSTANCE_NAME') ?: config('app.name'));

        // Cover page: logo
        $logoBlock = '';
        if ($logoFile) {
            $escapedLogo = $this->escapePath($logoFile);
            $logoBlock = "\\includegraphics[width=5cm]{{$escapedLogo}}\\\\[1.5cm]\n";
        }

        // Cover page: post credits table rows
        $creditsRows = '';
        foreach ($posts as $post) {
            $title   = $this->escape($post->title);
            $author  = $this->escape($post->user->name ?? '');
            $license = $this->escape($post->user->license ?? __('All rights reserved.'));
            $creditsRows .= "{$title} & {$author} & {$license} \\\\\n";
        }

        // Post body pages
        $postPages = '';
        foreach ($posts as $index => $post) {
            if (isset($postPdfs[$index])) {
                $safe = $this->escapePath($postPdfs[$index]);
                $postPages .= "\\includepdf[pages=-,pagecommand={\\thispagestyle{fancy}}]{{$safe}}\n";
            } else {
                $postPages .= $this->buildFallbackPage($post);
            }
        }

        $escapedTitle    = $this->escape($packTitle);
        $escapedInstance = $this->escape($instanceName);

        $header = "\\documentclass[12pt,a4paper]{article}\n"
            . "\\usepackage[utf8]{inputenc}\n"
            . "\\usepackage[T1]{fontenc}\n"
            . "\\usepackage{lmodern}\n"
            . "\\usepackage{pdfpages}\n"
            . "\\usepackage{fancyhdr}\n"
            . "\\usepackage{graphicx}\n"
            . "\\usepackage{geometry}\n"
            . "\\usepackage{lastpage}\n"
            . "\\usepackage{booktabs}\n"
            . "\\usepackage{rotating}\n"
            . "\\geometry{a4paper, margin=2cm}\n\n"
            . "% Page style with rotated page number at bottom-right\n"
            . "\\pagestyle{fancy}\n"
            . "\\fancyhf{}\n"
            . "\\fancyfoot[R]{\\rotatebox{90}{\\small\\thepage/\\pageref*{LastPage}}}\n"
            . "\\renewcommand{\\headrulewidth}{0pt}\n"
            . "\\renewcommand{\\footrulewidth}{0pt}\n\n"
            . "\\begin{document}\n\n"
            . "% ---- Cover page -------------------------------------------------------\n"
            . "\\thispagestyle{empty}\n"
            . "\\begin{titlepage}\n"
            . "\\centering\n";

        $titleSection = $logoBlock
            . "{\\Huge\\bfseries {$escapedInstance}}\\\\[0.8cm]\n"
            . "{\\LARGE {$escapedTitle}}\\\\[2cm]\n"
            . "\\begin{tabular}{@{}p{6cm}p{4cm}p{4cm}@{}}\n"
            . "\\toprule\n"
            . "\\textbf{" . $this->escape(__('Title')) . "} & "
            . "\\textbf{" . $this->escape(__('Author')) . "} & "
            . "\\textbf{" . $this->escape(__('License')) . "} \\\\\n"
            . "\\midrule\n"
            . $creditsRows
            . "\\bottomrule\n"
            . "\\end{tabular}\n"
            . "\\end{titlepage}\n\n";

        $footer = "\n\\end{document}\n";

        return $header . $titleSection . $postPages . $footer;
    }

    private function buildFallbackPage(Post $post): string
    {
        $title   = $this->escape($post->title);
        $author  = $this->escape($post->user->name ?? '');
        $license = $this->escape($post->user->license ?? __('All rights reserved.'));
        $desc    = $this->escape($post->description ?? '');

        return "\\newpage\n"
            . "\\begin{center}\n"
            . "{\\LARGE\\bfseries {$title}}\\\\[0.5cm]\n"
            . "\\textit{" . $this->escape(__('By')) . " {$author}}\\\\[0.3cm]\n"
            . "\\textit{" . $this->escape(__('License')) . ": {$license}}\\\\[1cm]\n"
            . "\\end{center}\n"
            . "{$desc}\n\n";
    }

    /**
     * Escape special LaTeX characters in a string.
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
     * Normalise a filesystem path for use inside a LaTeX command argument.
     */
    private function escapePath(string $path): string
    {
        return str_replace('\\', '/', $path);
    }
}
