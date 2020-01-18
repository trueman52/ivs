<?php

namespace App\UseCases\Booking;

use App\NewBookingCalculator;
use App\UseCases\Handler;
use Illuminate\Http\Request;

class CalculateBooking implements Handler
{
    use ManageBooking;

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

        $this->loadUnitInformation();

        $calculator = new NewBookingCalculator(
            $request->quantity,
            $this->unit,
            $this->periods,
            $this->groupedAddOns,
            $this->coupon,
            $request->adhocItems ? json_decode($request->adhocItems, true) : []
        );

        return $calculator->getCalculations();
    }
}