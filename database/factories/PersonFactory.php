<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Person;
use Faker\Generator as Faker;

$factory->define(Person::class, function (Faker $faker) {
    return [
        "first_name"=>$faker->firstName,
        "middle_name"=>$faker->text(20),
        "last_name"=>$faker->lastName
    ];
});
