<?php

namespace Tests\Feature;

use App\Models\Download;
use App\Models\Post;
use App\Models\User;
use App\Services\DownloadStatistics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadStatisticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting total downloads for a post.
     */
    public function test_get_total_downloads_for_post(): void
    {
        $post = Post::factory()->create();
        
        Download::factory()->count(5)->create(['post_id' => $post->id]);

        $service = new DownloadStatistics();
        $total = $service->getTotalDownloadsForPost($post);

        $this->assertEquals(5, $total);
    }

    /**
     * Test getting downloads by month for a post.
     */
    public function test_get_downloads_by_month_for_post(): void
    {
        $post = Post::factory()->create();
        
        // Create downloads in different months
        Download::factory()->create([
            'post_id' => $post->id,
            'downloaded_at' => now()->setMonth(1),
        ]);
        Download::factory()->create([
            'post_id' => $post->id,
            'downloaded_at' => now()->setMonth(1),
        ]);
        Download::factory()->create([
            'post_id' => $post->id,
            'downloaded_at' => now()->setMonth(2),
        ]);

        $service = new DownloadStatistics();
        $stats = $service->getDownloadsByMonth($post);

        $this->assertCount(2, $stats);
    }

    /**
     * Test getting downloads by month for a user.
     */
    public function test_get_user_downloads_by_month(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        
        // Create downloads in different months
        Download::factory()->count(3)->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'downloaded_at' => now()->setMonth(1),
        ]);
        Download::factory()->count(2)->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'downloaded_at' => now()->setMonth(2),
        ]);

        $service = new DownloadStatistics();
        $stats = $service->getUserDownloadsByMonth($user);

        $this->assertCount(2, $stats);
    }

    /**
     * Test getting downloads for a specific month.
     */
    public function test_get_downloads_for_post_by_month(): void
    {
        $post = Post::factory()->create();
        
        // Create downloads in January 2026
        Download::factory()->count(3)->create([
            'post_id' => $post->id,
            'downloaded_at' => '2026-01-15 10:00:00',
        ]);
        
        // Create downloads in February 2026
        Download::factory()->count(2)->create([
            'post_id' => $post->id,
            'downloaded_at' => '2026-02-15 10:00:00',
        ]);

        $service = new DownloadStatistics();
        $januaryDownloads = $service->getDownloadsForPostByMonth($post, 2026, 1);
        $februaryDownloads = $service->getDownloadsForPostByMonth($post, 2026, 2);

        $this->assertEquals(3, $januaryDownloads);
        $this->assertEquals(2, $februaryDownloads);
    }
}
