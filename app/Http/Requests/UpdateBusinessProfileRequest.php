<?php

namespace App\Http\Requests;

use App\Enums\BusinessAgeSize;
use App\Enums\BusinessRevenueSize;
use App\Enums\BusinessTeamSize;
use App\Enums\BusinessTicketSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusinessProfileRequest extends FormRequest
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
            'age'                 => [
                'required',
                Rule::in(BusinessAgeSize::toArray()),
            ],
            'revenue'             => [
                'required',
                Rule::in(BusinessRevenueSize::toArray()),
            ],
            'teamSize'           => [
                'required',
                Rule::in(BusinessTeamSize::toArray()),
            ],
            'averageTicketSize' => [
                'required',
                Rule::in(BusinessTicketSize::toArray()),
            ],
            'tags' => 'nullable|exists:tags,id'
        ];
    }
}
