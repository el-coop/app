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
            'title' =>  fake()->unique()->word,
            'content' => fake()->paragraph,
            'x' => fake()->numberBetween(0,500),
            'y' => fake()->numberBetween(0,500),
        ];
    }
}
