<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateCustomerAccountRequest;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:web']);
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.profile.account');
    }
    
    /**
     * Handle a profile update request for the application.
     *
     * @param  \App\Http\Requests\UpdateCustomerAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerAccountRequest $request)
    {
        $user = Auth::user();
        $user['password'] = bcrypt($request->get('password'));
        $user->save();
        return response()->json('password updated');
    }
    
}
