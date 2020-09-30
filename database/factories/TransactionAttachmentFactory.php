<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\TransactionAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionAttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionAttachment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'transaction_id' => Transaction::factory(),
            'name' => $this->faker->name,
        ];
    }
}
