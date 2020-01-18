<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class AddOnPolicy extends Policy
{

    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'add_ons';

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param                  $addOn
     *
     * @return bool
     */
    public function delete(User $user, $addOn)
    {
        return false;
    }

}
