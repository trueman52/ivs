<?php

namespace App\Nova\Actions;

use App\Models\Booking;
use App\Models\BookingPeriod;
use App\Models\BoothAssignment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class generateBoothAssignments extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    /**
     * Generate booth assignments for a booking.
     *
     * @param \App\Models\Booking            $booking
     * @param \Illuminate\Support\Collection $bookingPeriods
     */
    protected function generateAssignments(Booking $booking, Collection $bookingPeriods)
    {
        $assignments           = [];
        $defaultAllocatedBooths = json_encode([]);

        foreach ($bookingPeriods as $bookingPeriod) {
            $now = Carbon::now();

            $assignments[] = [
                'booking_period_id' => $bookingPeriod->id,
                'space_code'        => $booking->space->code,
                'unit_code'         => $booking->unit->code,
                'period_code'       => $bookingPeriod->period->code,
                'from'              => $bookingPeriod->period->fromDate,
                'to'                => $bookingPeriod->period->toDate,
                'allocated_booths'  => $defaultAllocatedBooths,
                'quantity'          => $bookingPeriod->quantity,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }

        DB::table('booth_assignments')->insert($assignments);
    }

    /**
     * Get the booking periods that we will need to create a
     * new booth assignment for.
     *
     * @param $booking
     *
     * @return \Illuminate\Support\Collection - Collection of BookingPeriod
     */
    protected function getCreatablePeriods($booking)
    {
        if ($booking->boothAssignments->isEmpty()) {
            return $booking->bookingPeriods;
        }

        $filter = $booking->boothAssignments->pluck('booking_period_id');

        return $booking->bookingPeriods->whereNotIn('id', $filter->all());
    }

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection    $models
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $booking) {
            $booking = $this->loadBookingData($booking);

            if ($booking->boothAssignments->isNotEmpty()) {
                $bookingPeriod = $booking->bookingPeriods->first();

                if ($this->wasBookingPeriodQuantityChanged($booking->boothAssignments->first(), $bookingPeriod)) {
                    $this->updateBoothAssignmentsQuantity($booking->boothAssignments, $bookingPeriod->quantity);
                }
            }

            $creatables = $this->getCreatablePeriods($booking);

            if ($creatables->isNotEmpty()) {
                $this->generateAssignments($booking, $creatables);
            }
        }
    }

    /**
     * Load all information needed in a single query.
     *
     * @param \App\Models\Booking $booking
     *
     * @return \App\Models\Booking
     */
    protected function loadBookingData(Booking $booking)
    {
        return $booking->loadMissing([
            'boothAssignments',
            'bookingPeriods.period',
            'space' => function ($q) {
                $q->select('id', 'code');
            },
            'unit'  => function ($q) {
                $q->select('id', 'code');
            },
        ]);
    }

    /**
     * @param \Illuminate\Support\Collection $assignments
     * @param int                            $quantity
     */
    protected function updateBoothAssignmentsQuantity(Collection $assignments, int $quantity)
    {
        $updatables = $assignments->pluck('id');

        BoothAssignment::whereIn('id', $updatables)
            ->update(['quantity' => $quantity]);
    }

    /**
     * @param \App\Models\BoothAssignment $assignment
     * @param \App\Models\BookingPeriod   $period
     *
     * @return bool
     */
    protected function wasBookingPeriodQuantityChanged(BoothAssignment $assignment, BookingPeriod $period)
    {
        return $assignment->quantity !== $period->quantity;
    }
}
