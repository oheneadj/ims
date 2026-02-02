<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = \App\Enums\ProductType::cases();

        foreach ($types as $type) {
            \App\Models\Category::firstOrCreate(
                ['slug' => $type->value],
                [
                    'name' => $type->label(),
                    'description' => 'Default category for ' . $type->label(),
                ]
            );
        }
    }
}
