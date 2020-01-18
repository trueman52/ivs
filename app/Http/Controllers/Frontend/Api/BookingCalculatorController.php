<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateBookingRequest;
use App\UseCases\Booking\CalculateBooking;

class BookingCalculatorController extends Controller
{
    public function calculate(CalculateBookingRequest $request)
    {
        $handler = new CalculateBooking();

        return response()->json($handler->handle($request), 200);
    }
}
