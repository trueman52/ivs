<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class TagPolicy extends Policy
{

    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'tags';
   
    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param                  $tag
     *
     * @return bool
     */
    public function delete(User $user, $tag)
    {
        return false;
    }
}
