<?php

namespace Ivs\CreateBookingTool\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    /**
     * Get all spaces by keyword
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        return response()->json([
            'spaces' => Space::with([
                'units' => function ($query) {
                    $query->published()
                        ->select('id', 'space_id', 'name')
                        ->with('periods', 'addOnGroups.addOns');
                },
            ])
                ->published()
                ->where('name', 'like', "%{$request->keyword}%")
                ->get(['id', 'name']),
        ]);
    }
}