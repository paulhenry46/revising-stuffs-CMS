<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Spatie\PdfToImage\Pdf;

class CreateThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pdf_path_light;
    protected $filename_path_thumbnail;
    protected $folder_path_thumbnail;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $pdf_path_light, 
        string $filename_path_thumbnail, 
        string $folder_path_thumbnail  )
    {
        $this->pdf_path_light = $pdf_path_light;
        $this->filename_path_thumbnail = $filename_path_thumbnail;
        $this->folder_path_thumbnail = $folder_path_thumbnail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdf = new Pdf(storage_path('app/public/'.$this->pdf_path_light.''));
        $pdf->saveImage(storage_path('app/public/'.$this->folder_path_thumbnail.'/'.$this->filename_path_thumbnail.''));
    }
}
