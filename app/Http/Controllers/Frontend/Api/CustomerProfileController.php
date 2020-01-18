<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerProfileRequest;
use App\UseCases\Customer\UpdateCustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerProfileController extends Controller
{
    /**
     * Show user details.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $request->user();

        $user->loadMissing('profile', 'profile.address', 'billing', 'billing.address');

        return response()->json(['user' => $user]);
    }


    /**
     * @param \App\Http\Requests\UpdateCustomerProfileRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCustomerProfileRequest $request)
    {
        try {
            (new UpdateCustomerProfile())->handle($request);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('Profile updated.');
    }
}
