<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * BusinessTicketSize enum
 */

/**
 * @method static BusinessTicketSize TICKET_SIZE_UNDER_10()
 * @method static BusinessTicketSize TICKET_SIZE_10_TO_50()
 * @method static BusinessTicketSize TICKET_SIZE_51_TO_100()
 * @method static BusinessTicketSize TICKET_SIZE_ABOVE_100()
 */
class BusinessTicketSize extends Enum
{
    private const TICKET_SIZE_UNDER_10  = 'under 10$';
    private const TICKET_SIZE_10_TO_50  = '$10 to $50';
    private const TICKET_SIZE_51_TO_100 = '$51 to $100';
    private const TICKET_SIZE_ABOVE_100 = 'above $100';
}
