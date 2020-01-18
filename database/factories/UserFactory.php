<?php

use App\Models\BankDetail;
use App\Models\BillingDetail;
use App\Models\BusinessDetail;
use App\Models\Coupon;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token'    => Str::random(10),
        'status'            => 'active',
    ];
});

$factory->state(User::class, 'customer', [])
    ->afterCreatingState(User::class, 'customer', function ($user, $faker) {
        factory(Profile::class)->create(['user_id' => $user->id]);

        factory(BillingDetail::class)->create([
            'detailable_type' => User::class,
            'detailable_id' => $user->id
        ]);

        factory(BusinessDetail::class)->create(['user_id' => $user->id]);

        factory(BankDetail::class)->create(['user_id' => $user->id]);
    });

$factory->state(User::class, 'superAdmin', [])
    ->afterCreatingState(User::class, 'superAdmin', function ($user, $faker) {
       $user->assignRole(Role::SUPER_ADMIN);
    });

$factory->state(User::class, 'withProfile', [])
        ->afterCreatingState(User::class, 'withProfile', function ($user, $faker) {
            factory(Profile::class)->create(['user_id' => $user->id]);
        });

$factory->state(User::class, 'withBilling', [])
        ->afterCreatingState(User::class, 'withBilling', function ($user, $faker) {
            factory(BillingDetail::class)->create([
                'detailable_type' => User::class,
                'detailable_id' => $user->id
            ]);
        });

$factory->state(User::class, 'withBusiness', [])
        ->afterCreatingState(User::class, 'withBusiness', function ($user, $faker) {
            factory(BusinessDetail::class)->create(['user_id' => $user->id]);
        });

$factory->state(User::class, 'withBank', [])
        ->afterCreatingState(User::class, 'withBank', function ($user, $faker) {
            factory(BankDetail::class)->create(['user_id' => $user->id]);
        });