<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Space;

class SpaceController extends Controller
{
    /**
     * Show space.
     *
     * @param \App\Models\Space $space
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Space $space)
    {
        return view('frontend.spaces.show', compact('space'));
    }
}
