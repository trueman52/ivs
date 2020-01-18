<?php

namespace App\Models;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    // Role names
    const SUPER_ADMIN = 'super admin';
    const ADMIN       = 'admin';
    const CUSTOMER    = 'customer';
    const COORDINATOR = 'coordinator';
}
