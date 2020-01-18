<?php

namespace App\UseCases\Booking;

use App\Enums\CouponStatus;
use App\Models\Coupon;
use App\Models\Unit;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

trait ManageBooking
{
    /**
     * @var \App\Models\Unit
     */
    protected $unit;

    /**
     * The selected periods for this booking.
     *
     * Collection of App\Models\Period
     *
     * @var \Illuminate\Support\Collection
     */
    protected $periods;

    /**
     * Collection of App\Models\GroupedAddOn
     *
     * @var \Illuminate\Support\Collection
     */
    protected $groupedAddOns;

    /**
     * Coupon used for this booking
     *
     * @var \App\Models\Coupon
     */
    protected $coupon;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * In order for us to perform mapping, we need to have
     * valid data for both grouped add-ons collection and
     * the request.
     *
     * @param \Illuminate\Support\Collection $groupedAddOns
     *
     * @return bool
     */
    protected function canMapQuantityToGroupedAddOns(Collection $groupedAddOns)
    {
        return $groupedAddOns->isEmpty() || !$this->request->groupedAddOns;
    }

    /**
     * @return \App\Models\Coupon
     */
    protected function getCoupon()
    {
        if (!$this->request->couponCode) return new Coupon();

        return Coupon::firstOrNew([
            'code'   => $this->request->couponCode,
            'status' => (string)CouponStatus::ACTIVE(),
        ]);
    }

    /**
     * Load relevant unit information to pass to our calculator.
     */
    protected function loadUnitInformation()
    {
        $this->unit = Unit::with([
            // Load only 'Period' that belongs to this unit. If a user passes
            // in an invalid period_unit id (which does not belong to this unit,
            // it will not be included.
            'periods'                   => function ($query) {
                $query->wherePivotIn('id', $this->request->periodUnitIds);
            },

            // Load only grouped add ons that belongs to this unit. If a user passes
            // in an invalid add_on_add_on_group ids (which does not belong to this unit,
            // it will be excluded
            'addOnGroups.groupedAddOns' => function ($query) {
                if ($this->request->groupedAddOns) {
                    $query->whereIn('id', Arr::pluck(json_decode($this->request->groupedAddOns, true), 'id'));
                }
            },

            'discounts',
        ])
            ->findOrFail($this->request->unitId);

        $this->periods       = $this->unit->periods;
        $this->groupedAddOns = $this->mapQuantityToGroupedAddOns(
            $this->unit
                ->addOnGroups
                ->pluck('groupedAddOns')
                ->flatten()
        );
        $this->coupon        = $this->getCoupon();
    }

    /**
     * Map quantity to our filtered grouped add ons.
     *
     * @param \Illuminate\Support\Collection $groupedAddOns
     *
     * @return \Illuminate\Support\Collection
     */
    protected function mapQuantityToGroupedAddOns(Collection $groupedAddOns)
    {
        if ($this->canMapQuantityToGroupedAddOns($groupedAddOns)) return $groupedAddOns;

        foreach (json_decode($this->request->groupedAddOns, true) as $info) {
            if (empty($info)) continue;

            $groupedAddOn = $groupedAddOns->firstWhere('id', $info['id']);

            $groupedAddOn->quantity = $info['quantity'];
        }

        return $groupedAddOns;
    }
}