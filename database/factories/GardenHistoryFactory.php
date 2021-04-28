<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\GardenHistory;
use Faker\Generator as Faker;

$factory->define(GardenHistory::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'width' => $faker->unique()->numberBetween(1, 100),
        'length' => $faker->unique()->numberBetween(1, 100),
        'grid' => '[{"row":0, "colour":"saddleBrown", "column":0},{"row":0, "colour":"saddleBrown", "column":1}]',
        'date' => now(),
    ];
});
