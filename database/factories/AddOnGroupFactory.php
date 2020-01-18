<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\AddOn;
use App\Models\AddOnGroup;
use Faker\Generator as Faker;

$factory->define(AddOnGroup::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    return [
        'backend_name'  => "Backend {$name}",
        'frontend_name' => "Frontend {$name}",
        'status'        => $faker->randomElement(\App\Enums\AddOnGroupStatus::toArray()),
    ];
});

$factory->afterCreating(AddOnGroup::class, function ($group, $faker) {
    $addOns   = factory(AddOn::class, 2)->create();
    $addOnsId = $addOns->pluck('id');
    $sync     = [];

    foreach ($addOnsId as $id) {
        $min = rand(1, 5);

        $sync[] = [
            'add_on_id'     => $id,
            'min'           => $min,
            'max'           => rand($min + 1, 100),
            'cost_per_unit' => rand(1000, 90000),
        ];
    }

    $group->addOns()->sync($sync);
});