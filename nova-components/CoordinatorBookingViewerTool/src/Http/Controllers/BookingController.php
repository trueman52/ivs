<?php

namespace Ivs\CoordinatorBookingViewerTool\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Get all bookings by unit.
     *
     * @param  App\Models\Unit         $unit
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Unit $unit, Request $request)
    {
        return Booking::with(['customer', 'bookingAddOns', 'bookingAddOns.groupedAddOn'])
                ->where('unit_id', $unit->id)
                ->latest()
                ->get();
    }
}