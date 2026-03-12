<?php

namespace App\Providers;
use App\Models\Post;
use App\Policies\PostPolicy;
use App\Models\Card;
use App\Policies\CardPolicy;
use App\Models\File;
use App\Policies\FilePolicy;
use App\Models\User;
use App\Policies\UserPolicy;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        Card::class => CardPolicy::class,
        File::class => FilePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
