<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the user "saved" event.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function saved(User $user)
    {
        // Update user role to Role::CUSTOMER
        if (request()->resource == 'customers') {
            $user->assignRole(Role::CUSTOMER);
        }
    }

}
