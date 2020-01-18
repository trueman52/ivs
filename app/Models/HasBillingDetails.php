<?php

namespace App\Models;

trait HasBillingDetails
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function billing()
    {
        return $this->morphOne(BillingDetail::class, 'detailable');
    }

    /**
     * @return string|null
     */
    public function getBillingCityAttribute()
    {
        return $this->billing && $this->billing->address ? $this->billing->address->city : null;
    }

    /**
     * @return string|null
     */
    public function getBillingCompanyNameAttribute()
    {
        return $this->billing ? $this->billing->company_name : null;
    }

    /**
     * @return string|null
     */
    public function getBillingCountryAttribute()
    {
        return $this->billing ? $this->billing->address ? $this->billing->address->country : null : null;
    }

    /**
     * @return string|null
     */
    public function getBillingCountryCodeAttribute()
    {
        if ($this->billing) {
            return $this->billing->country_code;
        }
        else {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getBillingEmailAttribute()
    {
        return $this->billing ? $this->billing->email : null;
    }

    /**
     * @return string|null
     */
    public function getBillingFirstNameAttribute()
    {
        return $this->billing ? $this->billing->first_name : null;
    }

    /**
     * @return string|null
     */
    public function getBillingFullContactNumberAttribute()
    {
        return $this->billing ? $this->billing->full_contact_number : null;
    }

    /**
     * @return string|null
     */
    public function getBillingLastNameAttribute()
    {
        return $this->billing ? $this->billing->last_name : null;
    }

    /**
     * @return string|null
     */
    public function getBillingPhoneNumberAttribute()
    {
        return $this->billing ? $this->billing->phone_number : null;
    }

    /**
     * @return string|null
     */
    public function getBillingPostalCodeAttribute()
    {
        return $this->billing ? $this->billing->address ? $this->billing->address->postal_code : null : null;
    }

    /**
     * @return string|null
     */
    public function getBillingStateAttribute()
    {
        return $this->billing ? $this->billing->address ? $this->billing->address->state : null : null;
    }

    /**
     * @return string|null
     */
    public function getBillingStreetAttribute()
    {
        return $this->billing ? $this->billing->address ? $this->billing->address->street : null : null;
    }
}