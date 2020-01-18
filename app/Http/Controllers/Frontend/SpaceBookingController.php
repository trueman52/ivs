<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Support\Facades\Session;

class SpaceBookingController extends Controller
{
    /**
     * Show space create booking page.
     *
     * @param \App\Models\Space $space
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Space $space)
    {
        $space->loadMissing('units', 'units.periods', 'units.addOnGroups', 'units.addOnGroups.addOns');
        
        return view('frontend.spaces.bookings', compact('space'));
    }
    
    /**
     * Show space things to note page.
     *
     * @param \App\Models\Space $space
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function thingsToNote(Space $space)
    {
        $space->loadMissing('units', 'units.tags');
        return view('frontend.spaces.thingsToNote', compact('space'));
    }
}
