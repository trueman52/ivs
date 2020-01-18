<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class Policy
{
    use HandlesAuthorization;

    public static $key;

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
        return $user->hasAnyPermission(['create ' . static::$key]);
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
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('delete ' . static::$key);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param                  $model
     *
     * @return mixed
     */
    public function forceDelete(User $user, $model)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('force delete ' . static::$key);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param                  $model
     *
     * @return mixed
     */
    public function restore(User $user, $model)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('restore ' . static::$key);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param                  $model
     *
     * @return mixed
     */
    public function update(User $user, $model)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermissionTo('update ' . static::$key);
    }

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

        return $user->hasPermissionTo('view ' . static::$key);
    }

    /**
     * Determine if the user can view any models at all.
     *
     * @param \App\Models\User $user
     *
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any ' . Str::plural(static::$key));
    }

}
