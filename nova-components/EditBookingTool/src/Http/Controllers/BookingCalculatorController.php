<?php

namespace Ivs\EditBookingTool\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ivs\EditBookingTool\UseCases\CalculateEditedBooking;

class BookingCalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        $handler = new CalculateEditedBooking();

        return $handler->handle($request);
    }
}