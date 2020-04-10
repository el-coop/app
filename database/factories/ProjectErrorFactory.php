<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProjectError;
use Faker\Generator as Faker;

$factory->define(ProjectError::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['serverSide', 'clientSide']),
        'url' => 'http://demo.test',
        'message' => 'test message',
        'exception' => 'Exception',
        'extra_data' => [],
    ];
});
