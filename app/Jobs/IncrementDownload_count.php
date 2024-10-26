<?php

namespace App\Jobs;

use App\Models\File;
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
        File::where('post_id', $numbers[0])->where('type', $this->type)->increment('download_count');
        
    }
}
