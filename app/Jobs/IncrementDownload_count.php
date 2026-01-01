<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Post;
use App\Services\DownloadService;
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
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $name,
        string $type,
        ?int $userId = null
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(DownloadService $downloadService): void
    {
        // Using preg_match to extract post ID from filename
        preg_match('/\d+/', $this->name, $numbers);
        
        if (!empty($numbers)) {
            $postId = $numbers[0];
            
            // Increment file download count (legacy system)
            File::where('post_id', $postId)
                ->where('type', $this->type)
                ->increment('download_count');
            
            // Record download in new event-based system
            $post = Post::find($postId);
            if ($post) {
                $user = $this->userId ? \App\Models\User::find($this->userId) : null;
                $downloadService->recordDownload($post, $user);
            }
        }
    }
}

