<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any posts (='manage all' function).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage all posts');
    }

     /**
     * Determine whether the user can moderate any posts (='moderate' function).
     */
    public function moderate(User $user): bool
    {
        return $user->hasPermissionTo('publish all posts');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Post $post): bool
    {
        /*By default, all gates and policies automatically return false if the incoming HTTP request was not initiated by an authenticated user. 
        However, you may allow these authorization checks to pass through to your gates and policies by declaring an "optional" type-hint*/
        
        if(($post->group_id == 1) and ($post->user_id !== $user->id)){//group id 1 is for private posts. So we check if the user is thos who created the post
            return false;
        }elseif(($post->group_id !== 2)){
            if((!array_key_exists($post->group_id, array_flip($user->groups->pluck('id')->toArray()))) and !(Group::where('id', $post->group_id)->first()->public)){
                //dump(array_flip($user->groups->pluck('id')->toArray()));
                //dump(array_key_exists($post->group_id, array_flip($user->groups->pluck('id')->toArray())));
                //dd($post->group_id);
                return false;
            }else{
                return true;
            }
        }else{
           
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

}
