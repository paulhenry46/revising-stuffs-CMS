<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CardPolicy
{
    public function before(User $user, $ability)
    {
        /*if ($user->hasRole('admin')) {
            return true;
        }*/
    }
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
     * Determine whether the user can update the model.
     */
    public function update(User $user, Card $card): bool
    {
        return $user->id === $card->post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Card $card): bool
    {
        return $user->id === $card->post->user_id;
    }

}
