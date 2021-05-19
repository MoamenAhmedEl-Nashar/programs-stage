<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Link;
use Faker\Generator as Faker;

$factory->define(Link::class, function (Faker $faker) {
    return [
        'name' => $faker->text(10),
        'path' => $faker->url(),
        'program_id' => 1,
        'user_id' => 1
    ];
});
