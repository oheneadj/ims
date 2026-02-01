<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleItem>
 */
class SaleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sale_id' => \App\Models\Sale::factory(),
            'product_id' => \App\Models\Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'unit_cost_price' => function (array $attributes) {
                return \App\Models\Product::find($attributes['product_id'])->cost_price;
            },
            'unit_selling_price' => function (array $attributes) {
                 return \App\Models\Product::find($attributes['product_id'])->selling_price;
            },
        ];
    }
}
