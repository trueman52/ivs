<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/booking', function () {
    return view('bookings.create');
});

/**
 * Application's web api endpoints.
 */
Route::prefix('web')
    ->namespace('App\Http\Controllers')
    ->group(function () {
        Route::resource('spaces', 'Frontend\Api\SpaceController')->only(['index', 'show']);
        Route::get('cart', 'Frontend\Api\CartController@store');
        Route::delete('cart', 'Frontend\Api\CartController@destroy');

        Route::middleware(['auth'])->group(function () {
            /**
             * Customer profile api.
             */
            Route::get('me', 'Frontend\Api\CustomerProfileController@show');
            Route::match(['put', 'patch'], 'me', 'Frontend\Api\CustomerProfileController@update');

            /**
             * Bookings api.
             */
            Route::get('bookings', 'Frontend\Api\BookingController@index');
            Route::post('bookings', 'Frontend\Api\BookingController@store');
            Route::get('bookings/{booking}', 'Frontend\Api\BookingController@show')->middleware('can:view,booking');
            Route::get('calculate-booking', 'Frontend\Api\BookingCalculatorController@show');

            /**
             * Coupons api.
             */
            Route::get('check-coupon', 'Frontend\Api\CheckCouponController@show');

            /**
             * Business detail api.
             */
            Route::get('my-business-detail', 'Frontend\Api\BusinessDetailController@show');
            Route::match(['put', 'patch'], 'my-business-detail', 'Frontend\Api\BusinessDetailController@update');

            /**
             * Bank detail api.
             */
            Route::get('my-bank', 'Frontend\Api\BankDetailController@show');
            Route::delete('my-bank', 'Frontend\Api\BankDetailController@destroy');
            Route::post('my-bank', 'Frontend\Api\BankDetailController@store');

            /**
             * Customer coupon api.
             */
            Route::get('my-coupons', 'Frontend\Api\CustomerCouponController@index');

            /**
             * Customer favourites api.
             */
            Route::get('my-favourites', 'Frontend\Api\UserFavouriteController@index');
            Route::delete('my-favourites/{favourite}', 'Frontend\Api\UserFavouriteController@destroy')->middleware('can:delete,favourite');
            Route::post('my-favourites', 'Frontend\Api\UserFavouriteController@store');
            

            //        Route::post('/paypal/order', 'Frontend\Api\PaypalController@store');
        });


    });

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Auth::routes();
    Route::get('forget/password', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.forget');
    Route::post('forget/password', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('forget/checkEmail', function () {
        return view('auth.passwords.checkEmail');
    })->name('password.checkEmail');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.resetstore');

    Route::get('email/verify/{token}', 'Frontend\VerificationController@show')->name('email.verify');
    Route::post('email/verify', 'Frontend\VerificationController@verify');
    Route::get('email/resend/{token}', 'Frontend\VerificationController@resend')->name('email.resend');
});

Route::group(['namespace' => 'App\Http\Controllers\Frontend'], function () {
    Route::get('/', 'IndexController@index')->name('home');
    Route::get('home', function () {
        return redirect('/');
    });

    Route::get('social/{provider}', 'SocialLoginController@redirectToProvider')->name('social');
    Route::get('social/{provider}/callback', 'SocialLoginController@handleProviderCallback')->name('social.callback');
    Route::get('spaces', function () {
        return view('frontend.spaces.index');
    })->name('spaces');
    Route::get('spaces/{space}', 'SpaceController@show')->name('spaces.show');
    
    Route::get('spaces/{space}/bookings', 'SpaceBookingController@show')->name('space.booking');
    Route::get('spaces/{space}/thingsToNote', 'SpaceBookingController@thingsToNote')->name('space.thingsToNote');

    Route::middleware(['auth'])->group(function () {
        Route::get('profile', function () {
            return view('frontend.profile.profile');
        })->name('profile');
        
        Route::get('business', function () {
            return view('frontend.profile.business');
        })->name('business');
        
        Route::get('bank', function () {
            return view('frontend.profile.bank');
        })->name('bank');
        
        Route::get('bank/create', function () {
            return view('frontend.profile.bankForm');
        })->name('bankForm');

        Route::get('account', 'AccountController@index')->name('account');
        Route::match(['put', 'patch'], 'account', 'AccountController@update');

        Route::get('favourites', function () {
            return view('frontend.profile.favourites');
        })->name('favourites');
        
        Route::get('bookings', function () {
            return view('frontend.bookings.index');
        })->name('bookings.index');
        
        Route::get('spaces/{space}/checkout', 'CheckoutController@index')->name('space.checkout');
    });
});
