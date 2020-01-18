<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Address::class, function (Faker $faker) {
    return [
        'street'      => $faker->streetAddress,
        'country'     => $faker->country,
        'state'       => $faker->state,
        'city'        => $faker->city,
        'postal_code' => $faker->postcode,
    ];
});
