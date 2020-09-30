<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder {
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run() {

        $entities = Entity::all();

        Transaction::factory()->count(60)->make()->each(function ($transaction) use ($entities) {
            $entity = $entities->random();
            $entity->transactions()->save($transaction);
        });
    }
}
