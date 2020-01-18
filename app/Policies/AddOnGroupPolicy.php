<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class AddOnGroupPolicy extends Policy
{

    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'add_on_groups';

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param                  $addOnGroup
     *
     * @return bool
     */
    public function delete(User $user, $addOnGroup)
    {
        return false;
    }

}
