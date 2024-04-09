<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    public function user()
    { 
        return $this->belongsTo(User::class);
    }
    public function post()
    { 
        return $this->belongsTo(Post::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'next_step' => 'date'
    ];
}
