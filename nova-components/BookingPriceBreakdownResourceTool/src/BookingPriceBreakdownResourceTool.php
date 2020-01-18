<?php

namespace Ivs\BookingPriceBreakdownResourceTool;

use Laravel\Nova\ResourceTool;

class BookingPriceBreakdownResourceTool extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Booking Price Breakdown Resource Tool';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'booking-price-breakdown-resource-tool';
    }
}
