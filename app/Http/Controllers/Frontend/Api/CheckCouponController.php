<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckCouponRequest;
use App\Models\Coupon;

class CheckCouponController extends Controller
{
    /**
     * @param \App\Http\Requests\CheckCouponRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(CheckCouponRequest $request)
    {
        return response()->json(['coupon' => Coupon::valid()->where('code', $request->couponCode)->first()]);
    }
}
