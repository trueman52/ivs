<?php

namespace App\Http\Requests;

use App\Enums\BusinessAgeSize;
use App\Enums\BusinessRevenueSize;
use App\Enums\BusinessTeamSize;
use App\Enums\BusinessTicketSize;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
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
            'userId'                              => 'required|exists:users,id',
            'unitId'                              => 'required|exists:units,id',
            'periodUnitIds'                       => 'required|exists:period_unit,id',
            'groupedAddOns'                       => 'nullable|json',
            'adhocItems'                          => 'nullable|json',
            'adhocItems.*.name'                   => 'required_with:adhocItems',
            'couponCode'                          => 'nullable|exists:coupons,code',
            'customerDetail.firstName'            => 'required|max:50',
            'customerDetail.lastName'             => 'required|max:100',
            'customerDetail.email'                => 'required|email',
            'customerDetail.contactNumber.number' => 'required',
            'customerDetail.contactNumber.code'   => 'required',
            'customerDetail.companyName'          => 'nullable|max:255',
            'customerDetail.address.street'       => 'required|max:255',
            'customerDetail.address.country'      => 'required|max:2',
            'customerDetail.address.postalCode'   => 'required',
            'billingDetail.firstName'             => 'required|max:50',
            'billingDetail.lastName'              => 'required|max:100',
            'billingDetail.email'                 => 'required|email',
            'billingDetail.contactNumber.number'  => 'required',
            'billingDetail.contactNumber.code'    => 'required',
            'billingDetail.companyName'           => 'nullable|max:255',
            'billingDetail.address.street'        => 'required|max:255',
            'billingDetail.address.country'       => 'required|max:2',
            'billingDetail.address.postalCode'    => 'required',
            'businessDetail.age'                  => [Rule::in(BusinessAgeSize::toArray())],
            'businessDetail.revenue'              => [Rule::in(BusinessRevenueSize::toArray())],
            'businessDetail.teamSize'             => [Rule::in(BusinessTeamSize::toArray())],
            'businessDetail.characteristics'      => [Rule::in(Tag::characteristics()->pluck('id')->all())],
            'businessDetail.averageTicketSize'    => [Rule::in(BusinessTicketSize::toArray())],
            'businessDetail.urls'                 => 'json',
            'application.description'             => 'required_with:businessDetail',
//            'application.attachments'             => 'required_with:businessDetail',
//            'application.attachments.*'           => 'file|lte:3',
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
