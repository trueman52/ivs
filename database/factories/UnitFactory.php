<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Discount;
use App\Models\Unit;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Unit::class, function (Faker $faker) {
    return [
        'name'                   => $faker->streetName,
        'code'                   => substr(Str::random(), 0, 5),
        'description'            => $faker->paragraph,
        'additional_information' => $faker->paragraph,
        'security_deposit'       => rand(50, 1000),
        'remarks'                => $faker->paragraph,
        'internal_notes'         => $faker->paragraph,
        'status'                 => $faker->randomElement(\App\Enums\UnitStatus::toArray()),
    ];
});

$factory->afterCreating(Unit::class, function ($unit, $faker) {
    $groups   = factory(\App\Models\AddOnGroup::class, 2)->create();

    $unit->addOnGroups()->sync($groups->pluck('id'));
});

