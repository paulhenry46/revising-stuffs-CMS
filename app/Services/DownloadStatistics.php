<?php

namespace App\Services;

use App\Models\Download;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DownloadStatistics
{
    /**
     * Get downloads by month for a specific post.
     *
     * @param Post $post
     * @return Collection Collection of objects with 'year', 'month', 'count'
     */
    public function getDownloadsByMonth(Post $post): Collection
    {
        return Download::where('post_id', $post->id)
            ->selectRaw('YEAR(downloaded_at) as year, MONTH(downloaded_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    /**
     * Get downloads by month and post type.
     *
     * @param string $type The post type (from types table)
     * @return Collection Collection of objects with 'year', 'month', 'count'
     */
    public function getDownloadsByMonthAndType(string $type): Collection
    {
        return Download::join('posts', 'downloads.post_id', '=', 'posts.id')
            ->join('types', 'posts.type_id', '=', 'types.id')
            ->where('types.name', $type)
            ->selectRaw('YEAR(downloaded_at) as year, MONTH(downloaded_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    /**
     * Get downloads by month for a specific user.
     *
     * @param User $user
     * @return Collection Collection of objects with 'year', 'month', 'count'
     */
    public function getUserDownloadsByMonth(User $user): Collection
    {
        return Download::where('user_id', $user->id)
            ->selectRaw('YEAR(downloaded_at) as year, MONTH(downloaded_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    /**
     * Get top downloaded posts for a specific month.
     *
     * @param int $year
     * @param int $month
     * @param int $limit Number of top posts to return (default: 10)
     * @return Collection Collection of posts with download_count
     */
    public function getTopPostsForMonth(int $year, int $month, int $limit = 10): Collection
    {
        return Download::join('posts', 'downloads.post_id', '=', 'posts.id')
            ->whereYear('downloaded_at', $year)
            ->whereMonth('downloaded_at', $month)
            ->select('posts.*', DB::raw('COUNT(downloads.id) as download_count'))
            ->groupBy('posts.id')
            ->orderBy('download_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get total downloads for a specific post.
     *
     * @param Post $post
     * @return int Total number of downloads
     */
    public function getTotalDownloadsForPost(Post $post): int
    {
        return $post->downloads()->count();
    }

    /**
     * Get downloads for a specific month and year for a post.
     *
     * @param Post $post
     * @param int $year
     * @param int $month
     * @return int Number of downloads
     */
    public function getDownloadsForPostByMonth(Post $post, int $year, int $month): int
    {
        return Download::where('post_id', $post->id)
            ->whereYear('downloaded_at', $year)
            ->whereMonth('downloaded_at', $month)
            ->count();
    }

    /**
     * Get download trends by day for a specific month.
     *
     * @param int $year
     * @param int $month
     * @return Collection Collection with 'day', 'count'
     */
    public function getDownloadsByDay(int $year, int $month): Collection
    {
        return Download::whereYear('downloaded_at', $year)
            ->whereMonth('downloaded_at', $month)
            ->selectRaw('DAY(downloaded_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();
    }

    /**
     * Get download count by post type.
     *
     * @return Collection Collection with type_name and count
     */
    public function getDownloadsByType(): Collection
    {
        return Download::join('posts', 'downloads.post_id', '=', 'posts.id')
            ->join('types', 'posts.type_id', '=', 'types.id')
            ->select('types.name as type_name', DB::raw('COUNT(downloads.id) as count'))
            ->groupBy('types.id', 'types.name')
            ->orderBy('count', 'desc')
            ->get();
    }
}
