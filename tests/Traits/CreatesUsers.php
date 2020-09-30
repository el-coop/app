<?php


namespace Tests\Traits;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait CreatesUsers {
    use RefreshDatabase;

    public function getDeveloper($data = []) {
        return User::factory()->developer()->create($data);
    }
}
