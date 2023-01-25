<?php

namespace Database\Factories;

use App\Models\Developer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt(123456),
            'remember_token' => Str::random(10),
        ];
    }

    public function developer() {
        return $this->state(function(array $attributes){
            $developer = Developer::factory()->create();
            return [
                'user_id' => $developer->id,
                'user_type' => Developer::class
            ];
        });
    }
}
