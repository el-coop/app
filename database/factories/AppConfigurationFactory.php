<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AppConfigurationFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'key' => fake()->unique()->word(),
            'value' => fake()->sentence()
        ];
    }
}
