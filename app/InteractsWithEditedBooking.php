<?php

namespace App;

use App\Models\Coupon;
use App\Models\GroupedAddOn;
use App\Models\Period;
use Illuminate\Support\Facades\Auth;

trait InteractsWithEditedBooking
{
    /**
     * @param \App\Models\GroupedAddOn $groupedAddOn
     *
     * @return mixed
     */
    protected function getBookingAddOn(GroupedAddOn $groupedAddOn)
    {
        return $this->booking->bookingAddOns->firstWhere('add_on_add_on_group_id', $groupedAddOn->id);
    }

    /**
     * @param array $adhocItem
     *
     * @return mixed
     */
    protected function getBookingAdhocItem(array $adhocItem)
    {
        return $this->booking->adhocItems->firstWhere('id', $adhocItem['id']);
    }

    /**
     * @param \App\Models\Period $period
     *
     * @return mixed
     */
    protected function getBookingPeriod(Period $period)
    {
        return $this->booking->bookingPeriods->firstWhere('period_id', $period->id);
    }

    /**
     * Checks if the period we that is provided exists in our booking.
     *
     * @param array $item
     *
     * @return bool
     */
    protected function isAdhocItemAssociatedToBooking(array $item)
    {
        if (!isset($item['booking_id'])) return false;

        return $this->booking->adhocItems->contains('booking_id', $item['booking_id']);
    }

    /**
     * Checks if the coupon that is provided exists in our booking.
     *
     * @param \App\Models\Coupon $coupon
     *
     * @return bool
     */
    protected function isCouponAssociatedToBooking(Coupon $coupon)
    {
        return $this->booking->usedCoupon->couponId === $coupon->id;
    }

    /**
     * True if the coupon can be applied by customer.
     *
     * @return bool
     */
    protected function isCouponUsableByCustomer(): bool
    {
        return $this->coupon->createdFor === null ||
            $this->coupon->createdFor === Auth::id();
    }

    /**
     * True if a coupon can be applied to space.
     *
     * @return bool
     */
    protected function isCouponUsableBySpace(): bool
    {
        return $this->coupon->spaceId === null ||
            $this->coupon->spaceId === $this->unit->spaceId;
    }

    /**
     *  Checks if the grouped add-on that is provided exists in our booking.
     *
     * @param \App\Models\GroupedAddOn $groupedAddOn
     *
     * @return mixed
     */
    protected function isGroupedAddOnAssociatedToBooking(GroupedAddOn $groupedAddOn)
    {
        return $this->booking->bookingAddOns->contains('add_on_add_on_group_id', $groupedAddOn->id);
    }

    /**
     * Checks if the period we that is provided exists in our booking.
     *
     * @param \App\Models\Period $period
     *
     * @return boolean
     */
    protected function isPeriodAssociatedToBooking(Period $period)
    {
        return $this->booking->bookingPeriods->contains('period_id', $period->id);
    }
}