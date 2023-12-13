<?php

namespace App\Policies;

use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilePolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function list(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, File $file): bool
    {
        return $user->id === $file->post->user_id;
    }
}
