<?php

namespace Ivs\CreateBookingTool\Http\Controllers;

use App\Exceptions\UnableToCreateBookingException;
use App\Http\Controllers\Controller;
use Ivs\CreateBookingTool\Http\Requests\StoreBookingRequest;
use Ivs\CreateBookingTool\UseCases\AdminCreateBooking;

class BookingController extends Controller
{
    /**
     * Create a booking for user.
     *
     * @param \Ivs\CreateBookingTool\Http\Requests\StoreBookingRequest $request
     *
     * @return mixed
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            $booking = (new AdminCreateBooking())->handle($request);
            
            return response()->json(['booking' => $booking]);
        }
        catch (UnableToCreateBookingException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}