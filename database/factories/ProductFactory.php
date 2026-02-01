<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = \App\Enums\ProductType::cases();
        $materials = \App\Enums\ProductMaterial::cases();
        
        $type = $this->faker->randomElement($types);
        $material = $this->faker->randomElement($materials);
        
        $name = $material->label() . ' ' . $type->label();
        if ($this->faker->boolean(40)) {
            $name .= ' with ' . $this->faker->randomElement(['Diamond', 'Ruby', 'Sapphire', 'Emerald', 'Pearl']);
        }
    
        $costPrice = $this->faker->randomFloat(2, 50, 2000);
        $markup = $this->faker->randomFloat(2, 1.2, 2.5); // 20% to 150% markup
        $sellingPrice = $costPrice * $markup;

        return [
            'name' => $name,
            'description' => $this->faker->sentence(),
            'sku' => strtoupper($this->faker->bothify('???-#####')),
            'type' => $type,
            'material' => $material,
            // 'weight' => $this->faker->randomFloat(2, 1, 50), // Removed as column doesn't exist
            'selling_price' => $sellingPrice,
            'cost_price' => $costPrice,
            'quantity_in_stock' => $this->faker->numberBetween(0, 100),
            'photo' => null, // Or a placeholder if we had one
        ];
    }
}
