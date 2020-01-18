<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * class BusinessTeamSize extends Enum
 * enum
 */

/**
 * @method static BusinessTeamSize TEAM_SIZE_UNDER_10()
 * @method static BusinessTeamSize TEAM_SIZE_11_TO_50()
 * @method static BusinessTeamSize TEAM_SIZE_ABOVE_50()
 */
class BusinessTeamSize extends Enum
{
    private const TEAM_SIZE_UNDER_10 = 'under 10';
    private const TEAM_SIZE_11_TO_50 = '11-50';
    private const TEAM_SIZE_ABOVE_50 = 'above 50';
}
