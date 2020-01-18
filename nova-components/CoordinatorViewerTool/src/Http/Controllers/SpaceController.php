<?php

namespace Ivs\CoordinatorViewerTool\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Space;

class SpaceController extends Controller
{
    /**
     * Get all spaces by coordinator.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        return Space::with(['coordinators'])
                    ->ownedBy($request->user()->id)
                    ->spaceName($request->search)
                    ->latest()
                    ->get();
    }
}