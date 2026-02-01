<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'category' => $this->faker->randomElement(\App\Enums\ExpenseCategory::cases()),
            'expense_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            // 'reference_number' => $this->faker->optional()->bothify('EXP-####'), // Removed
        ];
    }
}
