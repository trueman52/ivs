<?php

namespace App\UseCases\Booking;

use App\Exceptions\UnableToCreateBookingException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CustomerCreateBooking extends CreateBooking
{
    /**
     * The for request data.
     *
     * @var array
     */
    protected $data;

    /**
     * @var string|null
     */
    protected $paymentMethod;

    /**
     * Payment service charge object
     *
     * @var
     */
    protected $charge;

    /**
     * Handle the form request or api request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \App\Exceptions\UnableToCreateBookingException
     */
    public function handle(Request $request)
    {
        $paid    = 0;
        $payment = null;

        $this->setUsableData($request);

        // First, get all relevant information for the unit.
        $this->loadUnitInformation();

        // Once we have the relevant information, we may calculate the cost for our booking.
        $calculations = $this->calculateBooking();
        

        // if its payment by card, we will attempt to charge the full payment through
        // our payment service provider. If the payment is not successful, we will
        // throw an exception.
        if ($this->paymentMethod) {
            
            dd($this->customer->charge());
//            try {
                $payment = $this->customer->charge($calculations['grandTotal'], $this->paymentMethod);
//            }
//            catch (\Exception $e) {
//                throw new UnableToCreateBookingException($e->getMessage(), 500);
//            }
        }

        if ($payment) $paid = $calculations['grandTotal'];

        // Once the payment is successful, we will create the booking.
        $this->booking = $this->createBooking($calculations, $paid);
        // Store customer details
        $this->saveCustomerDetails();

        // Store billing details
        $this->saveBillingDetails();

        if ($request->businessDetails) {
            $this->saveBusinessDetails();
            $this->saveApplication();
        }
    }

    /**
     * Store the booking application request.
     */
    protected function saveApplication()
    {
        $application = $this->booking->application()->create(Arr::only($this->data['application'], ['description']));

        if (!$this->data['application']['attachments']) return;

        foreach ($this->data['application']['attachment'] as $attachment) {
            $application->addMedia($attachment)->toMediaCollection('application_attachments');
        }
    }

    /**
     * Store billing details for the booking.
     *
     * @return mixed
     */
    public function saveBillingDetails()
    {
        $detail = $this->booking->billing()->create(Arr::except($this->data['billingDetail'], ['address']));

        $detail->address()->create(Arr::only($this->data['billingDetail'], ['address']));
    }

    /**
     * Store business details.
     */
    protected function saveBusinessDetails()
    {
        $this->customer->business()->create($this->data['businessDetail']);
    }

    /**
     * Store customer details for the booking.
     *
     * @return mixed
     */
    public function saveCustomerDetails()
    {
        $detail = $this->booking
            ->customerDetail()
            ->create(array_merge(Arr::except($this->data['customerDetail'], ['address'])));
        $detail->address()->create(Arr::only($this->data['customerDetail'], ['address']));
    }

    protected function setUsableData(Request $request)
    {
        $this->data          = $request->all();
        $this->customer      = $request->user();
        $this->request       = $request;
        $this->paymentMethod = $request->paymentMethod;

        $this->customer->loadMissing('profile');
    }
}