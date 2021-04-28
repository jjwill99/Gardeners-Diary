<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\PlantActivity;
use Faker\Generator as Faker;

$factory->define(PlantActivity::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'time' => now(),
        'completed' => 0,
        'frequency' => 'null',
    ];
});
