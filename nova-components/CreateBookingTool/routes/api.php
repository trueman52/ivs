<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::group([
    'namespace'  => 'Ivs\CreateBookingTool\Http\Controllers',
], function () {
    Route::get('/customers', 'CustomerController@index');
    Route::get('/spaces', 'SpaceController@index');
    Route::get('/coupons', 'SpaceCouponController@index');
    Route::post('/bookings', 'BookingController@store');
});
