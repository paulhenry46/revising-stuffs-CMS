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
    protected $signature = 'rscms:generatePdfExcerpts {--queued : Dispatch jobs to the queue instead of running synchronously}';

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

        $runQueued = (bool) $this->option('queued');

        $bar = $this->output->createProgressBar($posts->count());
        $bar->start();

        $dispatched = 0;

        foreach ($posts as $post) {
            $lightFile = $post->files->first();
            if (! $lightFile) {
                $bar->advance();

                continue;
            }

            if ($runQueued) {
                ExtractPdfExcerpt::dispatch($post->id, $lightFile->file_path);
            } else {
                ExtractPdfExcerpt::dispatchSync($post->id, $lightFile->file_path);
            }
            $dispatched++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $modeLabel = $runQueued ? 'queued' : 'synchronous';
        $actionLabel = $runQueued ? 'Dispatched' : 'Processed';
        $this->info("{$actionLabel} excerpt generation for {$dispatched} post(s) in {$modeLabel} mode.");

        return 0;
    }
}
