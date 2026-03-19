<?php

namespace App\Policies;

use App\Models\Card;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class CardPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
    public function list(User $user, Post $post): bool
    {
        if( $user->id === $post->user_id){
            return true;
        }else{
            $curriculaIds = $user->getManagedCurriculaIds();
            return in_array($post->level->curriculum_id, $curriculaIds);
        }
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Post $post): bool
    {
        if( $user->id === $post->user_id){
            return true;
        }else{
            $curriculaIds = $user->getManagedCurriculaIds();
            return in_array($post->level->curriculum_id, $curriculaIds);
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Card $card): bool
    {

        $post = Post::whereHas('decks', function ($query) use ($card) {
        $query->whereHas('cards', function ($q) use ($card) {
            $q->where('cards.id', $card->id);
        });
    })
    ->first(); // Retourne un objet Post ou null

        if( $user->id === $post->user_id){
            return true;
        }else{
            $curriculaIds = $user->getManagedCurriculaIds();
            return in_array($post->level->curriculum_id, $curriculaIds);
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Card $card): bool
    {
        return $this->update($user, $card);
    }

    public function export(?User $user, Post $post)
    {
        return Auth::check()
        ? Response::allow()
        : Response::deny('You must to login to export cards.');
    }

}
