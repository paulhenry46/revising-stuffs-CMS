<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    public function hasFavorited(int $post_id): bool
    {
        
        if(in_array($post_id, $this->favorite_posts)){
            return true;
        }else{
            return false;
        }
    }

    public function hasCourse(int $course_id): bool
    {
        
        if(in_array($course_id, $this->courses_id)){
            return true;
        }else{
            return false;
        }
    }

    public function removeFavorite(int $post_id, User $user)
    {
        if (($key = array_search($post_id, $user->favorite_posts)) !== false) 
        {
                $array = $user->favorite_posts;
                unset($array[$key]);
                $user->favorite_posts = $array;
                $user->save();
        }
    }

    public function addFavorite(int $post_id, User $user)
    {
        $new_array = array_merge((array)$post_id, $user->favorite_posts);
        $user->favorite_posts = $new_array;
        $user->save();
    }


    protected $guard_name = 'sanctum';
    protected function getDefaultGuardName(): string { return 'sanctum'; }
    public function posts() 
    { 
    return $this->hasMany(Post::class); 
    }
    public function comments() 
    { 
    return $this->hasMany(Comment::class); 
    }
    public function likes() 
    { 
    return $this->hasMany(PostLike::class); 
    }

    public function steps() 
    { 
    return $this->hasMany(Step::class); 
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }
    
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'fcm_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'favorite_posts' => 'array',
        'courses_id' => 'array'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
