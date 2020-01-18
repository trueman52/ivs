<?php

namespace Ivs\CoordinatorBookingViewerTool\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Get unit details.
     *
     * @param \App\Models\Unit $unit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Unit $unit)
    {
        return $unit->loadMissing(['space']);
    }
}