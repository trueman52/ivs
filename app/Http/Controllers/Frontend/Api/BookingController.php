<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Exceptions\UnableToCreateBookingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\UseCases\Booking\CustomerCreateBooking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(['bookings' => $request->user()->bookings]);
    }

    /**
     * @param \App\Http\Requests\StoreBookingRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            $booking = (new CustomerCreateBooking())->handle($request);

            return response()->json(['booking' => $booking]);
        }
        catch (UnableToCreateBookingException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * @param \App\Models\Booking $booking
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Booking $booking)
    {
        $booking->loadMissing(['unit', 'bookingPeriods.period', 'bookingAddOns', 'customerDetail', 'billing']);

        return response()->json(['booking' => $booking]);
    }
}
