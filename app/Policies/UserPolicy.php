<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Admins bypass all policy checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can update a target user.
     * Co-admins can only manage users in their managed curricula who are not privileged.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        if ($authUser->hasRole('co-admin')) {
            $curriculaIds = $authUser->getManagedCurriculaIds();
            return in_array($targetUser->curriculum_id, $curriculaIds)
                && !$targetUser->hasAnyRole(['admin', 'moderator', 'co-admin']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete a target user.
     * Uses the same rules as update.
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        return $this->update($authUser, $targetUser);
    }
}
