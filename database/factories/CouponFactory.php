<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Coupon::class, function (Faker $faker) {
    $maxQuota = rand(3, 100);

    return [
        'code'       => substr(Str::random(), 0, 6),
        'valid_from' => Carbon::now(),
        'valid_to'   => (Carbon::now())->addDays(rand(1, 30)),
        'data'       => json_encode([
            'title'          => $faker->title,
            'description'    => $faker->paragraph,
            'rate'           => rand(5, 100),
            'rate_type'      => $faker->randomElement(\App\Enums\DiscountRateType::toArray()),
            'total_quota'    => $maxQuota,
            'quota_per_user' => rand(1, 3),
        ]),
        'status'     => $faker->randomElement(\App\Enums\CouponStatus::toArray()),
    ];
})
        ->afterMaking(Coupon::class, function ($coupon, $faker) {
            $coupon->created_by = factory(User::class)->create()->id;
        });

$factory->state(Coupon::class, 'personal', [])
        ->afterCreatingState(Coupon::class, 'personal', function ($coupon, $faker) {
            $coupon->customer()->associate(factory(User::class)->create())->save();
        });