<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\PlantLocationHistory;
use Faker\Generator as Faker;

$factory->define(PlantLocationHistory::class, function (Faker $faker) {
    return [
        'row' => '0',
        'column' => $faker->unique()->numberBetween(0, 1),
        'icon_location' => '1234',
    ];
});
