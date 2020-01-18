<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavouritePolicy
{
    use HandlesAuthorization;

    /**
     * Allow super admins to perform any actions.
     *
     * @param $user
     *
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param                  $model
     *
     * @return mixed
     */
    public function delete(User $user, $model)
    {
        return $user->id === $model->userId;
    }
}
