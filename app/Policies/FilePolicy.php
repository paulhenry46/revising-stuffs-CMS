<?php

namespace App\Policies;

use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }
    /**
     * Determine whether the user can view the model.
     */
    public function list(User $user, Post $post): bool
    {
        if( $user->id === $post->user_id){
            return true;
        }else{
            $curriculaIds = $user->getManagedCurriculaIds();
            return in_array($post->level->curriculum_id, $curriculaIds);
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Post $post): bool
    {
        if( $user->id === $post->user_id){
            return true;
        }else{
            $curriculaIds = $user->getManagedCurriculaIds();
            return in_array($post->level->curriculum_id, $curriculaIds);
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, File $file): bool
    {

         if( $user->id === $file->post->user_id){
            return true;
        }else{
            $curriculaIds = $user->getManagedCurriculaIds();
            return in_array($file->post->level->curriculum_id, $curriculaIds);
        }

    }
}
