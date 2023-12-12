<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any posts (='manage all' function).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage all posts');
    }

     /**
     * Determine whether the user can moderate any posts (='moderate' function).
     */
    public function moderate(User $user): bool
    {
        return $user->hasPermissionTo('publish all posts');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

}
