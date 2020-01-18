<?php

namespace App\Providers;

use App\Models\BookingPeriod;
use App\Models\BoothAssignment;
use App\Models\PeriodUnit;
use App\Models\Space;
use App\Models\Unit;
use App\Models\User;
use App\Models\FeaturedSpace;
use App\Observers\BookingPeriodObserver;
use App\Observers\BoothAssignmentObserver;
use App\Observers\PeriodUnitObserver;
use App\Observers\SpaceObserver;
use App\Observers\UnitObserver;
use App\Observers\UserObserver;
use App\Observers\FeaturedSpaceObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Space::observe(SpaceObserver::class);
        User::observe(UserObserver::class);
        Unit::observe(UnitObserver::class);
        PeriodUnit::observe(PeriodUnitObserver::class);
        BookingPeriod::observe(BookingPeriodObserver::class);
        BoothAssignment::observe(BoothAssignmentObserver::class);
        FeaturedSpace::observe(FeaturedSpaceObserver::class);
    }

}
