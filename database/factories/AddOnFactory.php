<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\AddOn;
use Faker\Generator as Faker;

$factory->define(AddOn::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    return [
        'backend_name'  => "Backend {$name}",
        'frontend_name' => "Frontend {$name}",
        'description'   => $faker->paragraph,
    ];
});