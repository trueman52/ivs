<?php

namespace Ivs\CreateBookingTool\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Get all users that's a customer.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        return response()->json([
            'users' => User::customer()
                ->where('email', 'like', "%{$request->keyword}%")
                ->get(['id', 'email', 'first_name', 'last_name']),
        ]);
    }
}