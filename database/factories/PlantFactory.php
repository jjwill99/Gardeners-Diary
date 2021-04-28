<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Plant;
use Faker\Generator as Faker;

$factory->define(Plant::class, function (Faker $faker) {
    return [
        'plant_name' => $faker->name,
        'icon' => $faker->file('\tmp', '\temp'),
    ];
});
