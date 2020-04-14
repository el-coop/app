<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TransactionAttachment;
use Faker\Generator as Faker;

$factory->define(TransactionAttachment::class, function (Faker $faker) {
    return [
        'transaction_id' => factory(\App\Models\Transaction::class),
        'name' => $faker->name,
    ];
});
