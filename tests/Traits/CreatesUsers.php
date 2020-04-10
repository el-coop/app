<?php


namespace Tests\Traits;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait CreatesUsers {
    use RefreshDatabase;

    public function getDeveloper($data = []) {
        return factory(User::class, $data)->state('developer')->create();
    }
}
