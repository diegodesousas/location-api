<?php

use App\Model\City;
use App\Model\State;
use Faker\Generator as Faker;

$factory->define(State::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'abbreviation' => str_random(2)
    ];
});
