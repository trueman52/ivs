<?php

namespace App\Policies;

use App\Models\User;
use Eminiarts\NovaPermissions\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends Policy
{

    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'users';


    public function attachRole(User $user, User $attchuser, Role $role)
    {
        return !$attchuser->roles->contains($role);
    }
   
    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param                  $attchUser
     *
     * @return bool
     */
    public function delete(User $user, $attchUser)
    {
        return false;
    }

}
