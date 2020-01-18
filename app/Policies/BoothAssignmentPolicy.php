<?php

namespace App\Policies;

use App\Models\User;

class BoothAssignmentPolicy extends Policy
{
    public static $key = 'booth_assignments';

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     * @throws \Exception
     */
    public function create(User $user)
    {
        return false;
    }
}
