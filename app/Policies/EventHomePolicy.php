<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class EventHomePolicy extends Policy
{

    use HandlesAuthorization;

    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'event_homes';

}
