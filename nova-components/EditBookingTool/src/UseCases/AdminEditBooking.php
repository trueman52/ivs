<?php

namespace Ivs\EditBookingTool\UseCases;

use App\EditBookingCalculator;
use App\Exceptions\UnableToCreateBookingException;
use App\InteractsWithEditedBooking;
use App\Models\Booking;
use App\Notifications\BookingEditedByAdmin;
use App\UseCases\Booking\ManageBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminEditBooking
{
    use ManageBooking, InteractsWithEditedBooking;

    /**
     * @var array $adhocItems
     */
    protected $adhocItems = [];

    /**
     * @var \App\Models\Booking
     */
    protected $booking;

    /**
     * Array of calculation results.
     *
     * @var array
     */
    protected $calculations = [];

    /**
     * Calculates the cost for the booking, and the calculation results.
     */
    function calculateBooking()
    {
        $this->calculations = (new EditBookingCalculator(
            $this->booking,
            $this->request->quantity,
            $this->unit,
            $this->periods,
            $this->groupedAddOns,
            $this->coupon,
            $this->adhocItems
        ))->getCalculations();
    }

    /**
     * Deletes items that are associated to the booking.
     *
     * @param \Illuminate\Support\Collection $removables
     * @param string                         $table
     *
     * @throws \Exception
     */
    public function deleteRemovablesFromBooking(Collection $removables, string $table)
    {

        dump($removables->first());
        $class = get_class($removables->first());

        $class::destroy($removables->pluck('id'));
    }

    /**
     * Get the booking add-ons that should be removed.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRemovableBookingAddOns()
    {
        if ($this->groupedAddOns->isEmpty() && $this->booking->bookingAddOns->isNotEmpty()) {
            return $this->booking->bookingAddOns;
        }

        if ($this->groupedAddOns->isEmpty()) return new Collection();

        return $this->booking
            ->bookingAddOns
            ->whereNotIn('add_on_add_on_group_id', $this->groupedAddOns->pluck('id'));
    }

    /**
     * Get the adhoc items that should be removed.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRemovableBookingAdhocItems()
    {
        if (empty($this->adhocItems) && $this->booking->adhocItems->isNotEmpty()) {
            return $this->booking->adhocItems;
        }

        if (empty($this->adhocItems)) return new Collection();

        return $this->booking
            ->adhocItems
            ->whereNotIn('id', Arr::pluck($this->adhocItems, 'id'));
    }

    /**
     * Get the booking periods that should be removed.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRemovableBookingPeriods()
    {
        if ($this->periods->isEmpty() && $this->booking->bookingPeriods->isNotEmpty()) {
            return $this->booking->bookingPeriods;
        }

        if ($this->periods->isEmpty()) return new Collection();

        return $this->booking
            ->bookingPeriods
            ->whereNotIn('period_id', $this->periods->pluck('id'));
    }

    /**
     * Handle the form request or api request.
     *
     * @param \App\Models\Booking      $booking
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \App\Exceptions\UnableToCreateBookingException
     */
    public function handle(Booking $booking, Request $request)
    {
        if (empty($request->periodUnitIds)) throw new UnableToCreateBookingException('A booking must have a period');

        $this->booking    = $booking;
        $this->request    = $request;
        $this->adhocItems = $request->adhocItems ? json_decode($request->adhocItems, true) : [];

        $this->loadBookingInformation();

        $this->loadUnitInformation();

        $this->calculateBooking();

        $this->updateBooking();

        if ($this->isNotifyingCustomer()) {
            $this->booking->customer->notify(new BookingEditedByAdmin($this->booking));
        }

        return $this->booking;
    }

    /**
     * @param array $adhocItem
     *
     * @return bool
     */
    protected function isAdhocItemAssociatedToBooking(array $adhocItem)
    {
        if (!isset($adhocItem['booking_id'])) return false;

        return $adhocItem['booking_id'] === $this->booking->id;
    }

    /**
     * Checks if a new coupon is added to booking.
     *
     * @return bool - true if its being replaced.
     */
    protected function isBookingCouponNewlyAdded()
    {
        return !$this->booking->usedCoupon && $this->coupon->exists;
    }

    /**
     * Checks if booking coupon got removed.
     *
     * @return bool - true if coupon is removed.
     */
    protected function isBookingCouponRemoved()
    {
        return $this->booking->usedCoupon && !$this->coupon->exists;
    }

    /**
     * Checks if booking coupon is being replaced.
     *
     * @return bool - true if its being replaced.
     */
    protected function isBookingCouponReplaced()
    {
        if ($this->booking->usedCoupon && $this->coupon->exists) {
            return $this->booking->usedCoupon->couponId !== $this->coupon->id;
        }

        return false;
    }

    /**
     * Checks if admin wants to notify customer about booking creation.
     *
     * @return bool
     */
    protected function isNotifyingCustomer()
    {
        return (bool)$this->request->notifyCustomer;
    }

    protected function loadBookingInformation()
    {
        $this->booking = Booking::with([
            'usedCoupon',
            'bookingPeriods',
            'bookingAddOns.addOn',
            'AdhocItems',
        ])->find($this->request->booking['id']);
    }

    /**
     * Updates changes made to the booking.
     *
     * @return mixed
     */
    public function updateBooking()
    {
        return DB::transaction(function () {
            $this->updateCoupon();

            $this->updateBookingAddOns();

            $this->updateBookingAdhocItems();

            $this->updateBookingPeriods();

            $this->updateBookingInformation();
        });
    }

    /**
     * Updates the add-ons for this booking.
     */
    protected function updateBookingAddOns()
    {
        $removables = $this->getRemovableBookingAddOns();

        if ($removables->isNotEmpty()) {
            $this->deleteRemovablesFromBooking($removables, 'booking_add_ons');
        }

        if ($this->groupedAddOns->isEmpty()) return;

        $newAddOns = [];

        foreach ($this->groupedAddOns as $groupedAddOn) {
            if ($this->isGroupedAddOnAssociatedToBooking($groupedAddOn)) {
                $bookingAddOn = $this->getBookingAddOn($groupedAddOn);

                $bookingAddOn->update(['quantity' => $groupedAddOn->quantity]);

                continue;
            }

            $newAddOns[] = $groupedAddOn;
        }

        if (!empty($newAddOns)) {
            $this->booking->saveAddOns(collect($newAddOns));
        }
    }

    /**
     * Updates the ad-hoc items for this booking.
     */
    protected function updateBookingAdhocItems()
    {
        $removables = $this->getRemovableBookingAdhocItems();

        if ($removables->isNotEmpty()) {
            $this->deleteRemovablesFromBooking($removables, 'adhoc_items');
        }

        if (empty($this->adhocItems)) return;

        $newAdhocItems = [];

        foreach ($this->adhocItems as $adhocItem) {
            if ($this->isAdhocItemAssociatedToBooking($adhocItem)) {
                $bookingAdhocItem = $this->getBookingAdhocItem($adhocItem);

                $bookingAdhocItem->update(['quantity' => $adhocItem['quantity']]);

                continue;
            }

            $newAdhocItems[] = $adhocItem;
        }

        if (!empty($newAdhocItems)) {
            $this->booking->saveAdhocItems($newAdhocItems);
        }
    }

    /**
     * Update the booking's price and calculations.
     */
    protected function updateBookingCost()
    {
        $this->booking->grandTotal = $this->calculations['grandTotal'];
        $this->booking->deposit    = $this->calculations['securityDeposit'];
        $this->data                = json_encode(['calculations' => $this->calculations]);
    }

    /**
     * Update the booking model.
     */
    protected function updateBookingInformation()
    {
        $this->updateBookingCost();

        $this->updateBookingNotes();

        $this->updateCalculationsMetaData();

        $this->booking->save();
    }

    /**
     * Store our calculations meta data to our booking.
     */
    protected function updateCalculationsMetaData()
    {
        $data = $this->booking->data;

        $data['calculations'] = $this->calculations;

        $this->booking->data = $data;
    }

    /**
     * Update booking notes.
     */
    protected function updateBookingNotes()
    {
        $this->booking->internalNotes = $this->request->internalNotes;
    }

    /**
     * Updates the periods for this booking.
     */
    protected function updateBookingPeriods()
    {
        $removables = $this->getRemovableBookingPeriods();

        if ($removables->isNotEmpty()) {
            $this->deleteRemovablesFromBooking($removables, 'booking_periods');
        }

        $newPeriods = [];

        foreach ($this->periods as $period) {
            if ($this->isPeriodAssociatedToBooking($period)) {
                $bookingPeriod      = $this->getBookingPeriod($period);
                $quantityDifference = $bookingPeriod->quantity - $this->request->quantity;

                // If there are no difference in quantity. We don't need to update anything.
                if ($quantityDifference === 0) continue;

                // Update the booking period quantity, and also update the remaining quantity
                // for the period based on the differences.
                $bookingPeriod->update(['quantity' => $this->request->quantity]);

                $period->pivot->remaining_quantity += $quantityDifference;

                $period->pivot->save();

                continue;
            }

            $newPeriods[] = $period;
        }

        if (!empty($newPeriods)) {
            $this->booking->savePeriods(collect($newPeriods), $this->request->quantity);
        }
    }

    /**
     * Update the coupon used for this booking.
     *
     * @throws \App\Exceptions\UnableToCreateBookingException
     */
    protected function updateCoupon()
    {
        if ($this->isBookingCouponRemoved()) {
            $this->booking->removeUsedCoupon();

            return;
        }

        if ($this->coupon->exists && !$this->coupon->isUsable()) {
            throw new UnableToCreateBookingException('The coupon created is unusable.');
        }

        if ($this->isBookingCouponNewlyAdded()) {
            $this->coupon->usedOn($this->booking);
        }

        if ($this->isBookingCouponReplaced()) {
            $this->booking->removeUsedCoupon();

            $this->coupon->usedOn($this->booking);
        }
    }
}
