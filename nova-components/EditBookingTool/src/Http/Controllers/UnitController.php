<?php

namespace Ivs\EditBookingTool\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Show all booking related information.
     *
     * @param \App\Models\Unit $unit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Unit $unit)
    {
        $unit->loadMissing([
            'periods',
            'addOnGroups.addOns',
        ]);

        return response()->json(['unit' => $unit]);
    }
}