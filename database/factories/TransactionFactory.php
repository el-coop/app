<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTimeBetween('-2 months'),
        'payer' => $faker->name(),
        'reason' => $faker->sentence(),
        'amount' => $faker->numberBetween(-1000, 1000),
        'comment' => $faker->paragraph()
    ];
});
