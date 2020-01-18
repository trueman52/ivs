<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Space;

class CheckoutController extends Controller
{
    /**
     * Show checkout.
     *
     * @param \App\Models\Space $space
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Space $space)
    {
        $space->loadMissing('units', 'units.tags');
        return view('frontend.spaces.checkout', compact('space'));
    }
}
