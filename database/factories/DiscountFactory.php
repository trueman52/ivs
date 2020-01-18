<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\DiscountRateType;
use App\Enums\DiscountStatus;
use App\Enums\DiscountType;
use App\Models\Discount;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Discount::class, function (Faker $faker) {
    $rateAmount   = rand(5, 30);
    $rateType     = $faker->randomElement(DiscountRateType::toArray());
    $discountType = $faker->randomElement(DiscountType::toArray());
    $data         = [];

    switch ($discountType) {
        case DiscountType::QUANTITY():
            $data = [
                'no_of_units' => 1,
                'rate'        => $rateAmount,
                'rate_type'   => $rateType,
            ];
            break;
        case DiscountType::PERIOD():
            $data = [
                'no_of_periods' => 1,
                'rate'          => $rateAmount,
                'rate_type'     => $rateType,
            ];
            break;
        default:
            $data = [
                'rate'       => $rateAmount,
                'rate_type'  => $rateType,
                'start_date' => Carbon::now(),
                'end_date'   => (Carbon::now())->addWeeks(3),
            ];
    }

    return [
        'name'   => "{$discountType} discount ({$rateAmount} {$rateType})",
        'type'   => $discountType,
        'data'   => json_encode($data),
        'status' => (string)DiscountStatus::ACTIVE(),
    ];
});
