<?php

namespace App\Models;

use App\Enums\CustomerStatus;
use App\Notifications\PasswordReset;
use Eloquence\Behaviours\CamelCasing;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable,
        HasRoles,
        Billable,
        HasBillingDetails,
        CamelCasing,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['fullName', 'profileContactNumber'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bank()
    {
        return $this->hasOne(BankDetail::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function business()
    {
        return $this->hasOne(BusinessDetail::class);
    }

    /**
     * @return string|null
     */
    public function characteristics()
    {
        return $this->morphToMany(Tag::class, 'taggable')->characteristics();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coordinatedSpaces()
    {
        return $this->belongsToMany(Space::class, 'coordinations')
            ->as('coordinations');
    }

    /**
     * Coupons created by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdCoupons()
    {
        return $this->hasMany(Coupon::class, 'created_by');
    }

    /**
     * User's favourited spaces.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    /**
     * @return string|null
     */
    public function getAccountAddressAttribute()
    {
        return $this->bank ? $this->bank->account_address : null;
    }

    /**
     * @return string|null
     */
    public function getAccountNumberAttribute()
    {
        return $this->bank ? $this->bank->account_number : null;
    }

    /**
     * @return string|null
     */
    public function getAccountTypeAttribute()
    {
        return $this->bank ? $this->bank->account_type : null;
    }

    /**
     * @return string|null
     */
    public function getAgeAttribute()
    {
        return $this->business ? $this->business->age : null;
    }

    /**
     * @return string|null
     */
    public function getAverageTicketSizeAttribute()
    {
        return $this->business ? $this->business->average_ticket_size : null;
    }

    /**
     * @return string|null
     */
    public function getBankCodeAttribute()
    {
        return $this->bank ? $this->bank->bank_code : null;
    }

    /**
     * @return string|null
     */
    public function getBankNameAttribute()
    {
        return $this->bank ? $this->bank->bank_name : null;
    }

    /**
     * @return string|null
     */
    public function getBranchCodeAttribute()
    {
        return $this->bank ? $this->bank->branch_code : null;
    }

    /**
     * @return string|null
     */
    public function getCityAttribute()
    {
        return $this->profile ? $this->profile->address ? $this->profile->address->city : null : null;
    }

    /**
     * @return string|null
     */
    public function getCompanyNameAttribute()
    {
        return $this->profile ? $this->profile->company_name : null;
    }

    /**
     * @return string|null
     */
    public function getCountryAttribute()
    {
        return $this->profile ? $this->profile->address ? $this->profile->address->country : null : null;
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return string|null
     */
    public function getHolderNameAttribute()
    {
        return $this->bank ? $this->bank->holder_name : null;
    }

    /**
     * @return string|null
     */
    public function getLocationAttribute()
    {
        return $this->bank ? $this->bank->location : null;
    }

    /**
     * @return string|null
     */
    public function getPostalCodeAttribute()
    {
        return $this->profile ? $this->profile->address ? $this->profile->address->postal_code : null : null;
    }

    /**
     * @return string|null
     */
    public function getProfileContactNumberAttribute()
    {
        if ($this->profile) {
            return $this->profile->full_contact_number;
        }
        else {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getProfileCountryCodeAttribute()
    {
        if ($this->profile) {
            return $this->profile->country_code;
        }
        else {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getRevenueAttribute()
    {
        return $this->business ? $this->business->revenue : null;
    }

    /**
     * @return string|null
     */
    public function getStateAttribute()
    {
        return $this->profile ? $this->profile->address ? $this->profile->address->state : null : null;
    }

    /**
     * @return string|null
     */
    public function getStreetAttribute()
    {
        return $this->profile ? $this->profile->address ? $this->profile->address->street : null : null;
    }

    /**
     * @return string|null
     */
    public function getTeamSizeAttribute()
    {
        return $this->business ? $this->business->team_size : null;
    }

    /**
     * @return string|null
     */
    public function getUrlsAttribute()
    {
        return $this->business ? $this->business->urls : null;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === (string)CustomerStatus::ACTIVE();
    }

    /**
     * Determines if the User is a Coordinator
     *
     * @return null
     */
    public function isCoordinator()
    {
        return $this->hasRole(Role::COORDINATOR);
    }

    /**
     * Determines if the User is a Customer
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->hasRole(Role::CUSTOMER);
    }

    /**
     * Determines if the User is a Super admin
     *
     * @return null
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Coupons created for user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedCoupons()
    {
        return $this->hasMany(Coupon::class, 'created_for');
    }

    /**
     * Scope by active status.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', CustomerStatus::ACTIVE());
    }

    /**
     * Scope a query to only include non customer user Role.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeCoordinator($query)
    {
        return $query->where(function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', '<>', Role::CUSTOMER);
            });
        })->orWhere(function ($query) {
            $query->whereDoesntHave('roles');
        });
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCustomer($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('name', Role::CUSTOMER);
        });
    }

    public function billingDetail()
    {
        return $this->morphOne(BillingDetail::class, 'detailable');
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function verificationCodes()
    {
        return $this->hasMany(VerificationCode::class, 'id', 'user_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class)
                    ->orderBy('id', 'DESC');
    }

    /**
     * @return mixed
     */
    public static function novaFilterSelectOptions()
    {
        return Cache::remember('nova-filter-user-options', 3600, function () {
            $users = self::setEagerLoads([])->get(['id', 'first_name', 'last_name']);

            return $users->mapWithKeys(function ($user) {
                return [$user->name => $user->id];
            })->toArray();
        });
    }
}
