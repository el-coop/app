<?php

namespace Database\Factories;

use App\Models\ScheduledAction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledActionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScheduledAction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement(['backupDatabase']),
            'frequency' => '* * * * *'
        ];
    }
}
