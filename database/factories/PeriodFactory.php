<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Period;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Period::class, function (Faker $faker) {
    return [
        'code'      => substr(Str::random(), 0, 5),
        'from_date' => (Carbon::now()),
        'to_date'   => (Carbon::now())->addWeeks(rand(1, 52)),
    ];
});