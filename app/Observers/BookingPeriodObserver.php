<?php

namespace App\Observers;

use App\Models\BookingPeriod;

class BookingPeriodObserver
{
    public function deleted(BookingPeriod $bookingPeriod)
    {
        $bookingPeriod->deleteBoothAssignments();
    }
}
