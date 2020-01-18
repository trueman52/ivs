<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBusinessProfileRequest;
use App\Http\Requests\UpdateCustomerProfileRequest;
use App\UseCases\Customer\UpdateBusinessProfile;
use App\UseCases\Customer\UpdateCustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessDetailController extends Controller
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

        return response()->json(['business' => $user->business]);
    }


    /**
     * @param \App\Http\Requests\UpdateBusinessProfileRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBusinessProfileRequest $request)
    {
        try {
            (new UpdateBusinessProfile())->handle($request);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('Business details updated.');
    }
}
