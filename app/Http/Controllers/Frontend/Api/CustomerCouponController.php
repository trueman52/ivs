<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerCouponController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json([
            'coupons' => $request->user()
                                 ->receivedCoupons()
                                 ->active()
                                 ->get(),
        ]);
    }
}
