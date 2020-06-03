<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TList;
use App\User;
use App\Task;
use Faker\Generator as Faker;

$factory->define(TList::class, function (Faker $faker) {
    return [
    		'user_id' => factory(User::class),
        'name' => $faker->text(100),
        'public' => false
    ];
});

$factory->state(TList::class, 'public', [
		'public' => true
]);

$factory->state(TList::class, 'with_entries', [])
				->afterCreatingState(TList::class, 'with_entries', function($list, $faker) {
	factory(Task::class, $faker->numberBetween(5, 10))->make([
				'user_id' => $list->user(),
				'list_id' => $list
		]);
});
