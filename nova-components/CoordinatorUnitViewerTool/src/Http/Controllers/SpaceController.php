<?php

namespace Ivs\CoordinatorUnitViewerTool\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Space;

class SpaceController extends Controller
{
    /**
     * Get space by coordinator.
     *
     * @param \App\Models\Space $space
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Space $space)
    {
        return $space->loadMissing(['units']);
    }
}