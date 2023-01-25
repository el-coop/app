<?php

namespace Database\Factories;

use App\Models\Debt;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebtFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Debt::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'entity_id' => Entity::factory(),
            'date' => fake()->date(),
            'currency' => fake()->randomElement(['₪', '$', '€']),
            'type' => 'fixed',
            'amount' => fake()->numberBetween(-100000, 100000),
        ];
    }
}
