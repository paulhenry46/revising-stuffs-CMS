<?php

namespace App\Console\Commands;

use App\Jobs\ExtractPdfExcerpt;
use App\Models\Post;
use Illuminate\Console\Command;

class GeneratePdfExcerpts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rscms:generatePdfExcerpts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate PDF excerpts for already published posts using only primary light files.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $posts = Post::query()
            ->where('published', true)
            ->whereHas('files', function ($query) {
                $query->where('type', 'primary light')->whereNotNull('file_path');
            })
            ->with(['files' => function ($query) {
                $query->where('type', 'primary light')
                    ->whereNotNull('file_path')
                    ->oldest('created_at')
                    ->limit(1);
            }])
            ->get();

        if ($posts->isEmpty()) {
            $this->info('No published posts with a primary light file found.');

            return 0;
        }

        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        $dispatched = 0;

        foreach ($posts as $post) {
            $lightFile = $post->files->first();
            if (! $lightFile) {
                $bar->advance();

                continue;
            }

            ExtractPdfExcerpt::dispatch($post->id, $lightFile->file_path);
            $dispatched++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Dispatched excerpt generation for {$dispatched} post(s).");

        return 0;
    }
}
