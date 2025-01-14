<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

   /**
     * Determine whether the user can update the profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $profileUser
     * @return bool
     */
    public function update(User $user, User $profileUser)
    {
        // Check if the user is the profile owner or if the user is an admin
        return $user->id === $profileUser->id || $user->is_admin;
    }
}
