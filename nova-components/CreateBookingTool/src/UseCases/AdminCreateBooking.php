<?php

namespace Ivs\CreateBookingTool\UseCases;

use App\Exceptions\UnableToCreateBookingException;
use App\Models\BillingDetail;
use App\Models\CustomerDetail;
use App\Models\User;
use App\UseCases\Booking\CreateBooking;
use Illuminate\Http\Request;

class AdminCreateBooking extends CreateBooking
{
    /**
     * The for request data.
     *
     * @var array
     */
    protected $data;

    /**
     * Handles the creation request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \App\Exceptions\UnableToCreateBookingException
     */
    public function handle(Request $request)
    {
        $this->setUsableData($request);

        // First, get all relevant information for the unit.
        $this->loadUnitInformation();

        // Once we have the relevant information,
        // we may calculate the cost for our booking, and create a booking
        $this->booking = $this->createBooking($this->calculateBooking());

        // Store customer details
        $this->saveCustomerDetails();

        // Store customer billing details.
        $this->saveBillingDetails();

        return $this->booking;
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed|void
     */
    protected function saveBillingDetails()
    {
        if (!$this->customer->billing()->exists()) {
            throw new UnableToCreateBookingException('Customer does not have billing details associated.');
        }

        $this->booking
            ->billing()
            ->save(
                BillingDetail::make($this->customer->billing->only([
                    'first_name',
                    'last_name',
                    'email',
                    'contact_number',
                    'company_name',
                ]))
            );
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed|void
     */
    protected function saveCustomerDetails()
    {
        CustomerDetail::create([
            'booking_id'     => $this->booking->id,
            'first_name'     => $this->customer->firstName,
            'last_name'      => $this->customer->lastName,
            'email'          => $this->customer->email,
            'contact_number' => $this->customer->profile->full_contact_number,
            'company_name'   => $this->customer->profile->companyName,
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * @param \Illuminate\Http\Request $request
     */
    protected function setUsableData(Request $request)
    {
        $this->data     = $request->all();
        $this->customer = User::with('profile')->findOrFail($request->userId);
        $this->request  = $request;
    }
}