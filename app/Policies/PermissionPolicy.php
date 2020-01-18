<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy extends Policy
{

    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'permissions';

}
