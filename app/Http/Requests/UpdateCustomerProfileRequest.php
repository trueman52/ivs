<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // User validation
            'user.firstName'                  => 'required|max:50',
            'user.lastName'                   => 'required|max:50',

            // User profile validation
            'profile.code'                    => 'required|max:3',
            'profile.number'                  => 'required|max:20',
            'profile.companyName'             => 'max:100',
            'profileAddress.country'          => 'required',
            'profileAddress.street'           => 'required',
            'profileAddress.postalCode'       => 'required|max:15',

            // User billing details validation
            'billingDetail.firstName'         => 'required|max:50',
            'billingDetail.lastName'          => 'required|max:50',
            'billingDetail.code'              => 'required|max:3',
            'billingDetail.number'            => 'required|max:20',
            'billingDetail.email'             => 'email',
            'billingDetail.companyName'       => 'string|max:100',
            'billingDetailAddress.country'    => 'required',
            'billingDetailAddress.street'     => 'required|max:50',
            'billingDetailAddress.postalCode' => 'required|max:15',
            'billingDetailAddress.city'       => 'required|max:30',
            'billingDetailAddress.state'      => 'required|max:30',
        ];
    }
}
