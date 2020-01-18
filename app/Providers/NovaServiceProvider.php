<?php

namespace App\Providers;

use Bakerkretzmar\NovaSettingsTool\SettingsTool;
use Eminiarts\NovaPermissions\NovaPermissions;
use Illuminate\Support\Facades\Gate;
use Ivs\CreateBookingTool\CreateBookingTool;
use Ivs\EditBookingTool\EditBookingTool;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Support\Facades\Auth;
use Ivs\CoordinatorViewerTool\CoordinatorViewerTool;
use Ivs\CoordinatorBookingViewerTool\CoordinatorBookingViewerTool;
use Ivs\CoordinatorUnitViewerTool\CoordinatorUnitViewerTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->hasPermissionTo('access admin dashboard');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        if(Auth::user()->isCustomer()) {
            Auth::logout();
        } else {
            return [
                (new NovaPermissions())->canSee(function ($request) {
                    return $request->user()->isSuperAdmin();
                }),

                (new SettingsTool)->canSee(function ($request) {
                    return $request->user()->isSuperAdmin();
                }),

                new CreateBookingTool(),
                new EditBookingTool(),
                            
                (new CoordinatorViewerTool())->canSee(function ($request) {
                    return $request->user()->isCoordinator();
                }),
                        
                (new CoordinatorUnitViewerTool())->canSee(function ($request) {
                    return $request->user()->isCoordinator();
                }),
                        
                (new CoordinatorBookingViewerTool())->canSee(function ($request) {
                    return $request->user()->isCoordinator();
                }),
            ];
        }
    }
}
