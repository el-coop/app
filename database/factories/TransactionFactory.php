<?php

namespace Database\Factories;

use App\Models\Entity;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        $currency = fake()->randomElement(['₪', '$', '€']);
        if ($currency == '₪') {
            $rate = 1;
        } else {
            $rate = fake()->randomFloat(2, 3, 5);
        }
        return [
            'entity_id' => Entity::factory(),
            'date' => fake()->dateTimeBetween('-2 months'),
            'reason' => fake()->sentence(),
            'amount' => fake()->numberBetween(-1000, 1000),
            'currency' => $currency,
            'rate' => $rate,
            'comment' => fake()->paragraph()
        ];
    }
}
