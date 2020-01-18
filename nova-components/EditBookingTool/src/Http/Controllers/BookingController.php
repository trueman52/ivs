<?php

namespace Ivs\EditBookingTool\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Ivs\EditBookingTool\UseCases\AdminEditBooking;

class BookingController extends Controller
{
    /**
     * Show all booking related information.
     *
     * @param \App\Models\Booking $booking
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Booking $booking)
    {
        $booking->loadMissing([
            'usedCoupon',
            'bookingPeriods',
            'bookingAddOns.addOn',
            'AdhocItems'
        ]);

        return response()->json(['booking' => $booking]);
    }

    /**
     * Update the booking.
     *
     * @param \App\Models\Booking      $booking
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function update(Booking $booking, Request $request)
    {
        return (new AdminEditBooking())->handle($booking, $request);
    }
}
