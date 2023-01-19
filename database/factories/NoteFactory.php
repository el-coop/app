<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' =>  $this->faker->unique()->word,
            'content' => $this->faker->paragraph,
            'x' => $this->faker->numberBetween(0,500),
            'y' => $this->faker->numberBetween(0,500),
        ];
    }
}
