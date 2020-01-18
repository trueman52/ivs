<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Filters\SpaceFilter;
use App\Http\Controllers\Controller;
use App\Models\Space;

class SpaceController extends Controller
{
    /**
     * Show all spaces.
     *
     * @param \App\Filters\SpaceFilter $filter
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(SpaceFilter $filter)
    {
        return response()->json([
            'spaces' => Space::published()->filter($filter)->paginate(8),
        ]);
    }

    /**
     * Show space.
     *
     * @param \App\Models\Space $space
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Space $space)
    {
        if (!$space->isPublished()) return abort(404);

        $space->loadMissing('media', 'tags', 'units', 'address');

        return response()->json(['space' => $space]);
    }
}
