<?php

namespace App\Policies;

use App\Models\Space;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpacePolicy extends Policy
{

    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'spaces';
    
    /**
     * @param \App\Models\User  $user
     * @param \App\Models\User  $attchUser
     * @param \App\Models\Space $space
     *
     * @return bool
     */
    public function attachUser(User $user, Space $space, User $attchUser)
    {
        return !$space->coordinators->contains($attchUser);
    }
   
    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param                  $space
     *
     * @return bool
     */
    public function delete(User $user, $space)
    {
        return false;
    }
}
