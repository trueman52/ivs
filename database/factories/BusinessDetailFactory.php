<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\BusinessDetail::class, function (Faker $faker) {
    $urls = [];
    $urls['website']   = $faker->url;
    $urls['facebook']  = $faker->url;
    $urls['instagram'] = $faker->url;
    
    return [
        'age'                 => $faker->randomElement(\App\Enums\BusinessAgeSize::toArray()),
        'revenue'             => $faker->randomElement(\App\Enums\BusinessRevenueSize::toArray()),
        'team_size'           => $faker->randomElement(\App\Enums\BusinessTeamSize::toArray()),
        'average_ticket_size' => $faker->randomElement(\App\Enums\BusinessTicketSize::toArray()),
        'urls'                => $urls,
    ];
});
