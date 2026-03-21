<?php

namespace App\Console\Commands;

use App\Jobs\AddWatermarkToPdf;
use App\Models\File;
use App\Models\Post;
use Illuminate\Console\Command;

class ApplyWatermarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rscms:applyWatermarks {--force : Re-generate watermarks even if they already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply watermark banners to all primary PDF files of published posts.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $force = $this->option('force');

        $query = File::whereHas('post', function ($q) {
            $q->where('published', true);
        })->where(function ($q) {
            $q->where('type', 'primary light')->orWhere('type', 'primary dark');
        });

        if (!$force) {
            $query->whereNull('watermarked_file_path');
        }

        $files = $query->get();

        if ($files->isEmpty()) {
            $this->info('No files to watermark.');
            return 0;
        }

        $bar = $this->output->createProgressBar($files->count());
        $bar->start();

        foreach ($files as $file) {
            AddWatermarkToPdf::dispatch($file->id);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Dispatched watermark jobs for {$files->count()} file(s).");

        return 0;
    }
}
