<?php

namespace App\Policies;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeckPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Deck $deck): bool
    {
        return $deck->deckable->id == $user->id;
    
    }
}