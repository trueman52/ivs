<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankDetailRequest extends FormRequest
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
            'bankName'      => 'required|max:50|string',
            'bankCode'      => 'required|max:10',
            'accountType'   => 'required|max:20|alpha',
            'branchCode'    => 'required|max:10',
            'accountNumber' => 'required|unique:bank_details,account_number',
            'holderName'    => 'required|max:50|string',
            'location'      => 'nullable|max:30',
            'address'       => 'nullable|max:150',
        ];
    }
}
