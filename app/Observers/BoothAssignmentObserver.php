<?php

namespace App\Observers;

use App\Models\BoothAssignment;
use App\Notifications\BoothAssigned;
use App\Observers\Traits\ExcludeAttributesFromInsertion;

class BoothAssignmentObserver
{
    use ExcludeAttributesFromInsertion;

    /**
     * @var array
     */
    protected $excludeFromRequest = [
        'notify_customer',
    ];

    public function saving(BoothAssignment $boothAssignment)
    {
        $this->excludeAttributesFromSaving($boothAssignment);

        if ($this->request->notify_customer) {
            $boothAssignment->booking->customer->notify(new BoothAssigned());
        }
    }
}
