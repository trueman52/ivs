<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    $contact_number = [];
    $contact_number['code'] = substr($faker->e164PhoneNumber, 1, 2);
    $contact_number['number'] = substr($faker->e164PhoneNumber, 3);
    return [
        'contact_number' => $contact_number,
        'company_name'   => $faker->company,
    ];
})->afterCreating(Profile::class, function ($profile, $faker) {
    $profile->address()->save(factory(\App\Models\Address::class)->make());
});
