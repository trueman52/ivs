<?php

namespace Ivs\EditBookingTool\UseCases;

use App\Models\Booking;
use App\EditBookingCalculator;
use App\UseCases\Booking\ManageBooking;
use App\UseCases\Handler;
use Illuminate\Http\Request;

class CalculateEditedBooking implements Handler
{
    use ManageBooking;

    /**
     * @var \App\Models\Booking;
     */
    protected $booking;

    /**
     * Handle the form request or api request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $this->request = $request;

        $this->loadBookingInformation();

        $this->loadUnitInformation();

        $calculator = new EditBookingCalculator(
            $this->booking,
            $request->quantity,
            $this->unit,
            $this->periods,
            $this->groupedAddOns,
            $this->coupon,
            $request->adhocItems ? json_decode($request->adhocItems, true) : []
        );

        return $calculator->getCalculations();
    }

    /**
     * Load booking related information.
     */
    public function loadBookingInformation()
    {
        $this->booking = Booking::with([
            'usedCoupon',
            'bookingPeriods',
            'bookingAddOns.addOn',
            'AdhocItems',
        ])->find(json_decode($this->request->booking, true)['id']);
    }
}