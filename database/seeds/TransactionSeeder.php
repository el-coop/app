<?php

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $entities = \App\Models\Entity::all();

        factory(Transaction::class, 60)->make()->each(function ($transaction) use ($entities) {
            $entity = $entities->random();
            $entity->transactions()->save($transaction);
        });
    }
}
