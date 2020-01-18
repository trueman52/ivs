<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->siteDesignsComposer();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    public function siteDesignsComposer()
    {
        View::composer(
            'frontend.index',
            \App\Http\ViewComposers\IndexComposer::class
        );
        View::composer(
            'frontend.spaces.show',
            \App\Http\ViewComposers\SpaceDetailsComposer::class
        );
    }
}
