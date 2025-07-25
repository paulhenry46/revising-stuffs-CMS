<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    public function post()
    { 
    return $this->belongsTo(Post::class); 
    }

    public function decks()
    {
        return $this->belongsToMany(Deck::class);
    }
}
