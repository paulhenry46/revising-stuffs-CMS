<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function course()
    { 
    return $this->belongsTo(Course::class); 
    }
    public function level()
    { 
    return $this->belongsTo(Level::class); 
    }
    public function user()
    { 
    return $this->belongsTo(User::class); 
    }
    public function files() 
    { 
    return $this->hasMany(File::class); 
    }
    public function events() 
    { 
    return $this->hasMany(Event::class); 
    }
    public function comments() 
    { 
    return $this->hasMany(Comment::class); 
    }
    public function cards() 
    { 
    return $this->hasMany(Card::class); 
    }
}
