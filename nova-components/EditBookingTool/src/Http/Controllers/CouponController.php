<?php

namespace Ivs\EditBookingTool\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Show all booking related information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        return response()->json([
            'coupons' => Coupon::where('space_id', $request->spaceId)
                ->orWhere('space_id', null)
                ->valid()
                ->get(),
        ]);
    }
}