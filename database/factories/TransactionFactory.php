<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
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
    public function definition(): array
    {
        return [
            'title'  => rtrim(ucfirst($this->faker->text(20)), '.'),
            'amount' => $this->faker->numberBetween(1000, 500000),
            'status' => $this->faker->numberBetween(1,3),
            'date'   => $this->faker->dateTimeBetween('-20 days', '-10 days'),
        ];
    }
}
