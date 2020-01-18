<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankDetailRequest;
use App\Http\Requests\UpdateBusinessProfileRequest;
use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Models\BankDetail;
use App\UseCases\Customer\UpdateBusinessProfile;
use App\UseCases\Customer\UpdateCustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankDetailController extends Controller
{
    /**
     * Delete user bank details.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $request->user()->bank->delete();

        return response()->json('Bank details deleted');
    }

    /**
     * Show user bank details.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json(['bank' => $user->bank]);
    }


    /**
     * Store user bank details
     *
     * @param \App\Http\Requests\StoreBankDetailRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBankDetailRequest $request)
    {
        BankDetail::create(array_merge(
            $request->all(),
            ['user_id' => $request->user()->id]
        ));

        return response()->json('Bank account added');
    }
}
