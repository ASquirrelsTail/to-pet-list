<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'name' => $faker->text(100),
        'completed' => false
    ];
});

$factory->state(Task::class, 'completed', [
		'completed' => true
]);
