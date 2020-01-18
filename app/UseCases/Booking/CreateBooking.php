<?php

namespace App\UseCases\Booking;

use App\Enums\BookingStatus;
use App\Exceptions\UnableToCreateBookingException;
use App\Models\Booking;
use App\NewBookingCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class CreateBooking
{
    use ManageBooking;

    /**
     * @var \App\Models\User
     */
    protected $customer;

    /**
     * @var \App\Models\Booking
     */
    protected $booking;

    /**
     * Calculates the cost for the booking, and the calculation results.
     *
     * @return array
     */
    function calculateBooking(): array
    {
        return (new NewBookingCalculator(
            $this->data['quantity'],
            $this->unit,
            $this->periods,
            $this->groupedAddOns,
            $this->coupon,
            json_decode($this->data['adhocItems'], true)
        ))->getCalculations();
    }

    /**
     * Creates booking.
     *
     * @param array $calculations
     *
     * @return \App\Models\Booking
     * @throws \App\Exceptions\UnableToCreateBookingException
     */
    public function createBooking(array $calculations): Booking
    {
        // if a valid coupon is provided. We will check if its usable.
        if ($this->coupon->exists && !$this->coupon->isUsable()) {
            throw new UnableToCreateBookingException('The coupon created is unusable.');
        }

        return DB::transaction(function () use ($calculations) {
            $booking = Booking::create([
                'user_id'     => $this->customer->id,
                'space_id'    => $this->unit->spaceId,
                'unit_id'     => $this->data['unitId'],
                'data'        => ['calculations' => $calculations],
                'grand_total' => $calculations['grandTotal'],
                'deposit'     => $calculations['securityDeposit'],
                'status'      => BookingStatus::ON_HOLD(),
            ]);
            activity('Booking Created')
            ->performedOn($booking)
            ->causedBy($this->customer)
            ->withProperties(['causer' => 'System', 'remarks' => ''])
            ->log('Booking ' .$booking->id . ' has been created');

            if ($this->groupedAddOns->isNotEmpty()) {
                $booking->saveAddOns($this->groupedAddOns);
            }

            $booking->savePeriods($this->periods, $this->data['quantity']);

            // If there are any discounts associated to the unit, store them.
            if ($this->unitHasDiscounts()) {
                $booking->saveDiscounts($this->unit->discounts);
            }

            if ($this->coupon->exists) {
                $this->coupon->usedOn($booking);
            }

            // If there are adhoc items, store them.
            if ($this->data['adhocItems']) {
                $booking->saveAdhocItems(json_decode($this->data['adhocItems'], true));
            }

            return $booking;
        });
    }

    /**
     * Handles the creation request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    abstract function handle(Request $request);

    /**
     * Save customer's billing details to booking.
     *
     * @return mixed
     * @throws \App\Exceptions\UnableToCreateBookingException
     */
    abstract protected function saveBillingDetails();

    /**
     * Save customer's details to booking.
     *
     * @return mixed
     */
    abstract protected function saveCustomerDetails();

    /**
     * Set request data and customer related data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    abstract protected function setUsableData(Request $request);

    /**
     * @return bool
     */
    protected function unitHasDiscounts()
    {
        return (bool)$this->unit->discounts()->count();
    }
}