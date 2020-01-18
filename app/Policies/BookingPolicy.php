<?php

namespace App\Policies;

use App\Models\User;

class BookingPolicy extends Policy
{
    public static $key = 'bookings';

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param                  $model
     *
     * @return mixed
     */
    public function view(User $user, $model)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('view ' . static::$key) || $model->userId === $user->id;
    }
}
