<?php

namespace App\UseCases\Customer;

use App\Models\Address;
use App\Models\BillingDetail;
use App\Models\Profile;
use App\UseCases\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UpdateCustomerProfile implements Handler
{
    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Models\Profile
     */
    protected $profile;

    /**
     * @var \App\Models\BillingDetail
     */
    protected $billingDetail;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Handle the form request or api request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $this->user    = $request->user();
        $this->request = $request;

        $this->updateUser();
        $this->updateProfile();
        $this->updateProfileAddress();
        $this->updateBillingDetail();
        $this->updateBillingDetailAddress();
    }

    /**
     * Update customer's billing details.
     */
    protected function updateBillingDetail()
    {
        $search = [
            'detailable_type' => \App\Models\User::class,
            'detailable_id' => $this->user->id
        ];
        //Format contactNumber value in json form according to 64robots/nova-fields
        $contactNumber['code']    = $this->request->billingDetail['code'];
        $contactNumber['number']  = $this->request->billingDetail['number'];
        $contact['contactNumber'] = $contactNumber;
        
        $this->billingDetail = BillingDetail::updateOrCreate(
            $search,
            array_merge($search, $contact, Arr::only($this->request->billingDetail, [
                'firstName',
                'lastName',
                'email',
                'companyName',
            ]))
        );
    }

    /**
     * Update customer's billing details' address
     */
    protected function updateBillingDetailAddress()
    {
        $search = [
            'addressable_type' => get_class($this->billingDetail),
            'addressable_id'   => $this->billingDetail->id,
        ];

        Address::updateOrCreate(
            $search,
            array_merge($search, $this->request->billingDetailAddress)
        );
    }

    /**
     * Update customer's profile.
     */
    protected function updateProfile()
    {
        $search                   = ['user_id' => $this->user->id];
        //Format contactNumber value in json form according to 64robots/nova-fields
        $contactNumber['code']    = $this->request->profile['code'];
        $contactNumber['number']  = $this->request->profile['number'];
        $contact['contactNumber'] = $contactNumber;

        $this->profile = Profile::updateOrCreate(
            $search,
            array_merge($search, $contact, Arr::only($this->request->profile, [
                'companyName',
            ]))
        );
    }

    /**
     * Update customer's profile address.
     */
    protected function updateProfileAddress()
    {
        $search = [
            'addressable_type' => get_class($this->profile),
            'addressable_id'   => $this->profile->id,
        ];

        Address::updateOrCreate(
            $search,
            array_merge($search, $this->request->profileAddress)
        );
    }

    /**
     * Update user model.
     */
    protected function updateUser()
    {
        $this->user->update(Arr::only($this->request->user, [
            'firstName',
            'lastName',
        ]));
    }
}