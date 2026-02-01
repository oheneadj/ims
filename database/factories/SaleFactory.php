<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(\App\Enums\PaymentStatus::cases());
        
        return [
            'customer_id' => \App\Models\Customer::factory(), // Defaults to factory, but overridden in seeder
            'sale_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'total_amount' => 0, // Calculated later based on items
            'total_cost' => 0, // Calculated later based on items
            'payment_status' => $status,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
