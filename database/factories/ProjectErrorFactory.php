<?php

namespace Database\Factories;

use App\Models\ProjectError;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectErrorFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectError::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'type' => fake()->randomElement(['serverSide', 'clientSide']),
            'url' => 'http://demo.test',
            'message' => 'test message',
            'exception' => 'Exception',
            'extra_data' => [],
        ];
    }
}
