<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Level extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function posts() 
    { 
        return $this->hasMany(Post::class); 
    }
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    public function Curriculum(){
    return $this->belongsTo(Curriculum::class);
}
}
