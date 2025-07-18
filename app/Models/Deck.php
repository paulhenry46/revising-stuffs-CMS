<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;



class Deck extends Model
{
    public $timestamps = false;
    public function user()
    { 
    return $this->belongsTo(User::class); 
    }

    public function cards()
    {
        return $this->belongsToMany(Card::class);
    }

     public function steps()
    { 
    return $this->hasMany(Step::class); 
    }

    /**

     * Get the parent deckable model (post or user).

     */

    public function deckable(): MorphTo
    {
        return $this->morphTo();
    }

}
