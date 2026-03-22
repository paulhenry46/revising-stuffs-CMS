<?php

namespace App\Services;

use App\Http\Controllers\CurriculumController;
use App\Models\Certificate;
use App\Models\Curriculum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class CertificateService
{
    /**
     * Generate a community commitment certificate PDF for the given user.
     *
     * @param  Certificate  $certificate  The stored certificate record
     * @param  User          $user
     * @param  Curriculum    $curriculum
     * @return array{pdfPath: string, tmpDir: string}
     *
     * @throws \RuntimeException if pdflatex fails or is unavailable
     */
    public function generate(Certificate $certificate, User $user, Curriculum $curriculum): array
    {
        $tmpDir = sys_get_temp_dir() . '/latex_cert_' . Str::random(10);
        mkdir($tmpDir, 0755, true);

        // Copy logo to temp dir (if exists)
        $logoFile = null;
        $logoPath = CurriculumController::logoPath($curriculum);
        if ($logoPath && file_exists($logoPath)) {
            $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $logoFile = 'logo.' . $ext;
            copy($logoPath, $tmpDir . '/' . $logoFile);
        }

        $instanceName = $curriculum->app_name ?: (env('INSTANCE_NAME') ?: config('app.name'));

        $latex = $this->buildLatex($certificate, $user, $instanceName, $logoFile);

        file_put_contents($tmpDir . '/certificate.tex', $latex);

        $finder = new ExecutableFinder();
        $pdflatexPath = $finder->find('pdflatex');

        if (!$pdflatexPath) {
            throw new \RuntimeException('pdflatex is not installed on this server.');
        }

        $command = [$pdflatexPath, '-interaction=nonstopmode', '-halt-on-error', 'certificate.tex'];

        $process = new Process($command, $tmpDir);

        try {
            $process->mustRun();
            // Second pass for cross-references
            $process->run();

            $pdfFile = $tmpDir . '/certificate.pdf';

            if (!file_exists($pdfFile)) {
                throw new \RuntimeException('PDF generation failed: output file not found.');
            }
        } catch (ProcessFailedException $exception) {
            $logPath = $tmpDir . '/certificate.log';
            $logTail = file_exists($logPath)
                ? substr(file_get_contents($logPath), -2000)
                : $exception->getMessage();
            throw new \RuntimeException('pdflatex failed. Log/Error: ' . $logTail);
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
        Certificate $certificate,
        User $user,
        string $instanceName,
        ?string $logoFile
    ): string {
        $escapedName     = $this->escape($certificate->name);
        $escapedInstance = $this->escape($instanceName);
        $certId          = $certificate->cert_id;
        $dateNow         = $certificate->issued_at->format('d/m/Y');
        $totalPosts      = $certificate->total_posts;
        $totalViews      = $certificate->total_views;

        $years = $certificate->years ?? [];

        // Build rows per year — group certified posts by academic year
        $yearRows = '';
        foreach ($years as $year) {
            $label = $this->escape($year);
            // Fetch certified posts for the user in this academic year
            [$start, $end] = $this->parseAcademicYear($year);
            $posts = Post::where('user_id', $user->id)
                ->whereNotNull('certified_at')
                ->when($start && $end, fn($q) => $q->whereBetween('certified_at', [$start, $end]))
                ->with('course')
                ->get();

            $count = $posts->count();
            if ($count === 0) {
                continue;
            }

            // Build course list
            $courseNames = $posts->map(fn($p) => $p->course?->name ?? '')->filter()->unique()->take(6)->implode(', ');
            $coursesEscaped = $this->escape($courseNames);

            $yearRows .= "    {$label} & \\small {$coursesEscaped} & {$count} \\\\\n";
        }

        if (empty($yearRows)) {
            $yearRows = "    \\multicolumn{3}{c}{\\textit{" . $this->escape(__('No certified posts found for the selected years.')) . "}} \\\\\n";
        }

        // Logo block
        $logoBlock = '';
        if ($logoFile) {
            $escapedLogo = $this->escapePath($logoFile);
            $logoBlock = "    \\includegraphics[width=1.5cm]{{$escapedLogo}}\n";
        }

        $school     = $this->escape($user->school?->name ?? '');
        $verifyUrl  = $this->escape(rtrim(config('app.url'), '/') . '/verify/' . $certId);
        $certIdEsc  = $this->escape($certId);

        $instanceEsc = $this->escape($instanceName);
        $totalPostsStr = (string) $totalPosts;
        $totalViewsStr = (string) $totalViews;

        return <<<LATEX
\\documentclass[11pt,a4paper]{article}
\\usepackage[utf8]{inputenc}
\\usepackage[T1]{fontenc}
\\usepackage[margin=2cm]{geometry}
\\usepackage[table]{xcolor}
\\usepackage{graphicx}
\\usepackage{tabularx}
\\usepackage{booktabs}
\\usepackage[hidelinks]{hyperref}

\\usepackage[sfdefault]{FiraSans}

\\definecolor{noir-titre}{gray}{0.1}
\\definecolor{gris-sombre}{gray}{0.4}
\\definecolor{gris-moyen}{gray}{0.7}
\\definecolor{gris-tres-clair}{gray}{0.96}

\\begin{document}

% --- EN-TÊTE ---
\\begin{minipage}{0.6\\textwidth}
    \\begin{minipage}{2cm}
{$logoBlock}    \\end{minipage}
    \\begin{minipage}{5cm}
    {\\color{noir-titre}\\LARGE\\bfseries {$instanceEsc}} \\\\
    {\\color{gray}\\small \\href{{$verifyUrl}}{Plateforme de Partage}}
    \\end{minipage}
\\end{minipage}
\\begin{minipage}{0.4\\textwidth}
    \\raggedleft
    {\\footnotesize R\\'{E}F : \\texttt{{$certIdEsc}}}\\\\
    {\\footnotesize \\'{E}MIS LE : \\texttt{{$dateNow}}}
\\end{minipage}

\\vspace{1.5cm}

% --- TITRE ---
\\begin{center}
    {\\huge\\bfseries BILAN DE CONTRIBUTION} \\\\
    \\vspace{0.2cm}
    {\\color{lightgray}\\rule{5cm}{1.5pt}}
\\end{center}

\\vspace{1cm}

% --- IDENTIT\\'{E} ---
\\noindent
\\begin{minipage}[t]{0.5\\textwidth}
    {\\color{gris-moyen}{\\textbf{\\'ETUDIANT}}} \\\\
    {\\Large {$escapedName}} \\\\
    \\textit{{$school}}
\\end{minipage}
\\begin{minipage}[t]{0.5\\textwidth}
    \\raggedleft
    {\\color{gris-moyen}{\\textbf{STATUT}}}\\\\
    {\\color{noir-titre}\\scshape \\space \\textbf{Contributeur} } \\\\
    \\small Ressources certifi\\'ees : {$totalPostsStr}
\\end{minipage}

\\vspace{1.5cm}

% --- CORPS DU TEXTE ---
\\section*{Engagement Communautaire}
La plateforme \\textbf{{$instanceEsc}} atteste par la pr\\'esente de l'implication de l'\\'etudiant susnomm\\'e dans la mutualisation des ressources p\\'edagogiques. Les contributions list\\'ees ci-dessous ont \\'et\\'e soumises \\`a mod\\'eration et valid\\'ees pour leur pertinence par la communaut\\'e.

\\vspace{0.5cm}

% --- TABLEAU DES CONTRIBUTIONS ---
\\renewcommand{\\arraystretch}{1.5}
\\rowcolors{2}{gris-tres-clair}{white}
\\begin{tabularx}{\\textwidth}{lXc}

    \\textbf{Ann\\'ee} & \\textbf{Unit\\'e d'enseignement} & \\textbf{Ressources Valid\\'ees} \\\\

{$yearRows}    
    \\textbf{TOTAL} & & \\textbf{{$totalPostsStr} Fiches} \\\\
    \\bottomrule
\\end{tabularx}

\\vspace{1cm}

% --- IMPACT ---
\\begin{center}
    \\textit{Ces ressources ont \\'et\\'e consult\\'ees et t\\'el\\'echarg\\'ees \\textbf{{$totalViewsStr}} fois sur la plateforme.}
\\end{center}

\\vfill

% --- PIED DE PAGE ---
\\begin{minipage}{0.7\\textwidth}
    \\begin{flushleft}
        \\scriptsize
        \\textbf{Note de transparence~:} Ce document est un relev\\'e d'activit\\'e b\\'en\\'evole issu du r\\'eseau collaboratif RSCMS. Il ne constitue pas un dipl\\^ome d'\\'Etat. \\\\
        \\textbf{V\\'erification~:} L'authenticit\\'e de ce bilan peut \\^etre v\\'erifi\\'ee en ligne sur \\href{{$verifyUrl}}{{$verifyUrl}}.
    \\end{flushleft}
\\end{minipage}

\\end{document}
LATEX;
    }

    /**
     * Parse an academic year string like "2024 - 2025" into a date range.
     * Returns [start, end] as strings suitable for whereBetween, or [null, null].
     */
    private function parseAcademicYear(string $year): array
    {
        // Matches "2024 - 2025", "2024-2025", "2024/2025", etc.
        if (preg_match('/(\d{4})\s*[-\/]\s*(\d{4})/', $year, $m)) {
            return ["{$m[1]}-09-01 00:00:00", "{$m[2]}-08-31 23:59:59"];
        }
        // Single year
        if (preg_match('/^(\d{4})$/', trim($year), $m)) {
            return ["{$m[1]}-01-01 00:00:00", "{$m[1]}-12-31 23:59:59"];
        }
        return [null, null];
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
