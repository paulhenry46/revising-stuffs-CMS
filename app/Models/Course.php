<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function posts() 
    { 
        return $this->hasMany(Post::class); 
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class);
    }
}
