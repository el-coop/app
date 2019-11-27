<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    $currency = $faker->randomElement(['₪', '$', '€']);
    if ($currency == '₪') {
        $rate = 1;
    } else {
        $rate = $faker->randomFloat(2, 3, 5);
    }
    return [
        'date' => $faker->dateTimeBetween('-2 months'),
        'reason' => $faker->sentence(),
        'amount' => $faker->numberBetween(-1000, 1000),
        'currency' => $currency,
        'rate' => $rate,
        'comment' => $faker->paragraph()
    ];
});
