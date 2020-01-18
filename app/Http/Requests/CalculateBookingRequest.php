<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class CalculateBookingRequest extends FormRequest
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

    protected function checkAdhocItemsJson()
    {
        $adhocItems = json_decode($this->adhocItems, true);

        if (empty($adhocItems)) return;

        $arrayValidator = Validator::make(['adhocItems' => $adhocItems], [
            'adhocItems.*.name'     => 'required|string',
            'adhocItems.*.amount'   => 'required|integer',
            'adhocItems.*.quantity' => 'required|integer',
        ]);

        if ($arrayValidator->fails()) {
            $this->validator->messages()->merge($arrayValidator->messages());
        }
    }

    protected function checkGroupedAddOnsJson()
    {
        $groupedAddOns = json_decode($this->groupedAddOns, true);

        if (empty($groupedAddOns)) return;

        $arrayValidator = Validator::make(['groupedAddOns' => $groupedAddOns], [
            'groupedAddOns.*.id'       => 'required|exists:add_on_add_on_group,id',
            'groupedAddOns.*.quantity' => 'required|integer',
        ]);

        if ($arrayValidator->fails()) {
            $this->validator->messages()->merge($arrayValidator->messages());
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'unitId'                   => 'required|exists:units,id',
            'periodUnitIds'            => 'required|exists:period_unit,id',
            'groupedAddOns'            => 'nullable|json',
            'adhocItems'               => 'nullable|json',
            'adhocItems.*.name'        => 'required_with:adhocItems',
            'couponCode'               => 'nullable|exists:coupons,code',
        ];
    }

    /**
     * Transform json parameter to array.
     *
     * @param array $keys - The request's param keys.
     */
    public function transformJsonParameters(array $keys)
    {
        foreach ($keys as $key) {
            $this->{$key} = json_decode($this->{$key}, true);
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator(&$validator)
    {
        $this->validator = &$validator;

        $this->validator->after(function () {
            $this->checkGroupedAddOnsJson();
            $this->checkAdhocItemsJson();
        });
    }
}
