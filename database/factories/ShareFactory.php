<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Share;
use Faker\Generator as Faker;

$factory->define(Share::class, function () {
    return [
        'complete' => false,
        'create' => false,
        'update' => false,
        'delete' => false,
    ];
});

$factory->state(Share::class, 'complete', [
		'complete' => true
]);

$factory->state(Share::class, 'create', [
		'create' => true
]);

$factory->state(Share::class, 'update', [
		'update' => true
]);

$factory->state(Share::class, 'delete', [
		'delete' => true
]);

