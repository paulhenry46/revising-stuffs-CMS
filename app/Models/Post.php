<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
    public function type()
    { 
    return $this->belongsTo(Type::class);
    }
    public function user()
    { 
    return $this->belongsTo(User::class); 
    }
    public function group()
    { 
    return $this->belongsTo(Group::class);
    }
    public function files() 
    { 
    return $this->hasMany(File::class); 
    }

    public function decks(): MorphMany
    {
        return $this->morphMany(Deck::class, 'deckable');
    }

    public function events() 
    { 
    return $this->hasMany(Event::class); 
    }
    public function comments() 
    { 
    return $this->hasMany(Comment::class); 
    }
    public function likes() 
    { 
    return $this->hasMany(PostLike::class); 
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    protected $withCount = [
        'likes',
    ];

    public function isLiked(): bool
    {
        if (auth()->user()){
            return auth()->user()->likes()->forPost($this)->count();
        }
        if (($ip = request()->ip()) && ($userAgent = request()->userAgent())) {
            return $this->likes()->forIp($ip)->forUserAgent($userAgent)->count();
        }
        return false;
    }

    public function removeLike(): bool
    {
        if(auth()->user()){
            return auth()->user()->likes()->forPost($this)->delete();
        }
        if (($ip = request()->ip()) && ($userAgent = request()->userAgent())) {
            return $this->likes()->forIp($ip)->forUserAgent($userAgent)->delete();
        }
        return false;
    }
    public function downloads(): int{
        $files = $this->files;
        $count = 0;
        foreach($files as $file){
            $count = $count + $file->download_count;
        }
        return $count;
    }
}
