<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run() {
        $user = User::factory()->developer()->create([
            'email' => 'admin@elcoop.test'
        ]);
    }
}
