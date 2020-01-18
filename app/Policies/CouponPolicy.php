<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class CouponPolicy extends Policy
{

    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'coupons';
   
    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param                  $coupon
     *
     * @return bool
     */
    public function delete(User $user, $coupon)
    {
        return false;
    }

}
