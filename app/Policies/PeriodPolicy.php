<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class PeriodPolicy extends Policy
{

    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'periods';
   
    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param                  $period
     *
     * @return bool
     */
    public function delete(User $user, $period)
    {
        return false;
    }

}
