<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\PlantHistory;
use Faker\Generator as Faker;

$factory->define(PlantHistory::class, function (Faker $faker) {
    return [
        'plant_name' => $faker->name,
        'icon' => $faker->file('\tmp', '\temp'),
    ];
});
