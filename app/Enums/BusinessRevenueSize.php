<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * BusinessRevenueSize enum
 */

/**
 * @method static BusinessRevenueSize REVENUE_SIZE_UNDER_100K()
 * @method static BusinessRevenueSize REVENUE_SIZE_100K_TO_1M()
 * @method static BusinessRevenueSize REVENUE_SIZE_1M_TO_5M()
 * @method static BusinessRevenueSize REVENUE_SIZE_ABOVE_5M()
 */
class BusinessRevenueSize extends Enum
{
    private const REVENUE_SIZE_UNDER_100K = 'under 100K';
    private const REVENUE_SIZE_100K_TO_1M = '100K to 1M';
    private const REVENUE_SIZE_1M_TO_5M   = '1M to 5M';
    private const REVENUE_SIZE_ABOVE_5M   = 'above 5M';
}
