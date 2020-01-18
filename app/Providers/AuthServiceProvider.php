<?php

namespace App\Providers;

use App\Models\AddOn;
use App\Models\AddOnGroup;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\Favourite;
use App\Models\FeaturedSpace;
use App\Models\Period;
use App\Models\Space;
use App\Models\Tag;
use App\Models\Unit;
use App\Models\User;
use App\Models\BoothAssignment;
use App\Policies\AddOnGroupPolicy;
use App\Policies\AddOnPolicy;
use App\Policies\BookingPolicy;
use App\Policies\BoothAssignmentPolicy;
use App\Policies\CouponPolicy;
use App\Policies\DiscountPolicy;
use App\Policies\EventHomePolicy;
use App\Policies\FavouritePolicy;
use App\Policies\PeriodPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\SpacePolicy;
use App\Policies\TagPolicy;
use App\Policies\UnitPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        AddOnGroup::class      => AddOnGroupPolicy::class,
        AddOn::class           => AddOnPolicy::class,
        Coupon::class          => CouponPolicy::class,
        User::class            => UserPolicy::class,
        Discount::class        => DiscountPolicy::class,
        FeaturedSpace::class   => EventHomePolicy::class,
        Period::class          => PeriodPolicy::class,
        Permission::class      => PermissionPolicy::class,
        Role::class            => RolePolicy::class,
        Space::class           => SpacePolicy::class,
        Tag::class             => TagPolicy::class,
        Unit::class            => UnitPolicy::class,
        Favourite::class       => FavouritePolicy::class,
        Booking::class         => BookingPolicy::class,
        BoothAssignment::class => BoothAssignmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
