<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Program;
use Faker\Generator as Faker;

$factory->define(Program::class, function (Faker $faker) {
    return [
        'name' => $faker->text(20),
        'description' => $faker->sentence(),
        'version' => $faker->numberBetween(1, 3),
        'database' => 'MySQL',
        'web_or_desktop' => 'web',
        'ip' => $faker->ipv4(),
        'icon' => 'iconLink',
        'user_id' => 1
    ];
});
