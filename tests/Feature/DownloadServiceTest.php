<?php

namespace Tests\Feature;

use App\Models\Download;
use App\Models\Post;
use App\Models\User;
use App\Services\DownloadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that recording a download creates a download record.
     */
    public function test_recording_download_creates_download_record(): void
    {
        $post = Post::factory()->create(['downloads_total' => 0]);
        $user = User::factory()->create();
        
        $service = new DownloadService();
        $download = $service->recordDownload($post, $user);

        $this->assertInstanceOf(Download::class, $download);
        $this->assertEquals($post->id, $download->post_id);
        $this->assertEquals($user->id, $download->user_id);
        $this->assertDatabaseHas('downloads', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that recording a download increments the post counter.
     */
    public function test_recording_download_increments_post_counter(): void
    {
        $post = Post::factory()->create(['downloads_total' => 5]);
        $user = User::factory()->create();
        
        $service = new DownloadService();
        $service->recordDownload($post, $user);

        $post->refresh();
        $this->assertEquals(6, $post->downloads_total);
    }

    /**
     * Test that recording a download works with null user.
     */
    public function test_recording_download_works_with_null_user(): void
    {
        $post = Post::factory()->create(['downloads_total' => 0]);
        
        $service = new DownloadService();
        $download = $service->recordDownload($post, null);

        $this->assertInstanceOf(Download::class, $download);
        $this->assertEquals($post->id, $download->post_id);
        $this->assertNull($download->user_id);
        $this->assertDatabaseHas('downloads', [
            'post_id' => $post->id,
            'user_id' => null,
        ]);
    }
}
