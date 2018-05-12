<?php

use App\Model\City;
use App\Model\State;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
        'state_id' => function() {
            return factory(State::class)->create()->id;
        }
    ];
});
