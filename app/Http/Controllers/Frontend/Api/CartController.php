<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Store user cart details.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //Delete user cart details.
        Session::forget(['unitId', 'unit', 'calculation', 'requestData']);
        
        $unit = Unit::find($request->unitId);
        Session::put('unitId', $request->unitId);
        Session::put('unit', $unit);
        Session::put('calculation', $request->calculation);
        Session::put('requestData', $request->requestData);
        return response()->json('Session value updated.');
    }
    
    /**
     * Delete user cart details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        Session::forget(['unitId', 'unit', 'calculation', 'requestData']);
        return response()->json('Session value destroyed');
    }
}
