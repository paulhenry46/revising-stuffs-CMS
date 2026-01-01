<?php

namespace Tests\Unit;

use App\Models\Download;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a download can be created.
     */
    public function test_download_can_be_created(): void
    {
        $download = Download::create([
            'post_id' => 1,
            'user_id' => 1,
            'downloaded_at' => now(),
        ]);

        $this->assertInstanceOf(Download::class, $download);
        $this->assertEquals(1, $download->post_id);
        $this->assertEquals(1, $download->user_id);
    }

    /**
     * Test that downloaded_at is cast to datetime.
     */
    public function test_downloaded_at_is_cast_to_datetime(): void
    {
        $download = Download::create([
            'post_id' => 1,
            'user_id' => 1,
            'downloaded_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $download->downloaded_at);
    }

    /**
     * Test that download can have null user_id.
     */
    public function test_download_can_have_null_user_id(): void
    {
        $download = Download::create([
            'post_id' => 1,
            'user_id' => null,
            'downloaded_at' => now(),
        ]);

        $this->assertNull($download->user_id);
    }
}
