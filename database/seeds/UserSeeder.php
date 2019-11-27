<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $user = factory(\App\Models\User::class)->make([
            'email' => 'admin@elcoop.test'
        ]);
        
        factory(\App\Models\Developer::class)->create()->user()->save($user);
    }
}
