<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ScheduledAction;
use Faker\Generator as Faker;

$factory->define(ScheduledAction::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\Models\User::class),
        'action' => $faker->randomElement(['backupDatabase']),
        'frequency' => '* * * * *'
    ];
});
