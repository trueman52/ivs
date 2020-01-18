<?php

namespace App\Observers;

use App\Models\PeriodUnit;

class PeriodUnitObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param \App\Models\PeriodUnit $periodUnit
     *
     * @return void
     */
    public function creating(PeriodUnit $periodUnit)
    {
        // Update remaining_quantity based on max_quantity
        $periodUnit->remaining_quantity = $periodUnit->max_quantity;
    }
    
    /**
     * Handle the user "updating" event.
     *
     * @param \App\Models\PeriodUnit $periodUnit
     *
     * @return void
     */
    public function updating(PeriodUnit $periodUnit)
    {
        // Update remaining_quantity based on max_quantity
        $periodUnit->remaining_quantity = $periodUnit->max_quantity;
    }
    
}
