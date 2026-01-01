<?php

namespace App\Jobs;

use App\Models\Download;
use App\Models\File;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Spatie\PdfToImage\Pdf;

class IncrementDownload_count implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $name;
    protected $type;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $name,
        string $type )
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Using preg_match_all to extract numbers
        preg_match('/\d+/', $this->name, $numbers);
        
        //Increment file
        $postId = $numbers[0];
        File::where('post_id', $postId)->where('type', $this->type)->increment('download_count');


                Download::create([
                'post_id' => $postId,
                'downloaded_at' => now(),
                ]);

        
    }
}
