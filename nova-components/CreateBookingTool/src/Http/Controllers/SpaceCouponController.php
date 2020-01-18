<?php

namespace Ivs\CreateBookingTool\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class SpaceCouponController extends Controller
{
    /**
     * Get all valid coupons that the space can use.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        return response()->json([
            'coupons' => Coupon::where('space_id', $request->spaceId)
                ->orWhere('space_id', null)
                ->valid()
                ->get()
        ]);
    }
}