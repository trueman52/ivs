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

Route::namespace('Ivs\EditBookingTool\Http\Controllers')->group(function () {
    Route::get('/calculate-booking', 'BookingCalculatorController@calculate');
    Route::get('/bookings/{booking}', 'BookingController@show');
    Route::put('/bookings/{booking}', 'BookingController@update');
    Route::get('/units/{unit}', 'UnitController@show');
    Route::get('/coupons', 'CouponController@index');
});

