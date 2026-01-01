<?php

namespace App\Services;

use App\Models\Download;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DownloadService
{
    /**
     * Record a download event for a post.
     *
     * @param Post $post The post being downloaded
     * @param User|null $user The user downloading (null for anonymous)
     * @return Download The created download record
     */
    public function recordDownload(Post $post, ?User $user = null): Download
    {
        return DB::transaction(function () use ($post, $user) {
            // Create the download event record
            $download = Download::create([
                'post_id' => $post->id,
                'user_id' => $user?->id,
                'downloaded_at' => now(),
            ]);

            // Increment the global counter on the post
            $post->incrementDownloads();

            return $download;
        });
    }
}
