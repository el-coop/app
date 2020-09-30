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
        $currency = $this->faker->randomElement(['₪', '$', '€']);
        if ($currency == '₪') {
            $rate = 1;
        } else {
            $rate = $this->faker->randomFloat(2, 3, 5);
        }
        return [
            'entity_id' => Entity::factory(),
            'date' => $this->faker->dateTimeBetween('-2 months'),
            'reason' => $this->faker->sentence(),
            'amount' => $this->faker->numberBetween(-1000, 1000),
            'currency' => $currency,
            'rate' => $rate,
            'comment' => $this->faker->paragraph()
        ];
    }
}
