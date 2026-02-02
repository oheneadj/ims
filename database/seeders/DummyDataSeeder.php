<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create other random users
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('user');
        });

        // 2. Create Products
        $products = \App\Models\Product::factory(50)->create();

        // 3. Create Customers
        $customers = \App\Models\Customer::factory(20)->create();

        // 4. Create Expenses (distributed over year)
        \App\Models\Expense::factory(30)->create();

        // 5. Create Sales & Linked Data
        foreach ($customers as $customer) {
            // Each customer has 0-5 sales
            $sales = \App\Models\Sale::factory(rand(0, 5))->create([
                'customer_id' => $customer->id,
            ]);

            foreach ($sales as $sale) {
                // Each sale has 1-5 items
                $saleItems = \App\Models\SaleItem::factory(rand(1, 5))->create([
                    'sale_id' => $sale->id,
                    'product_id' => $products->random()->id,
                ]);

                // Recalculate Sale Total
                $totalAmount = $saleItems->sum(function($item) {
                     return $item->quantity * $item->unit_selling_price;
                });
                $totalCost = $saleItems->sum(function($item) {
                     return $item->quantity * $item->unit_cost_price;
                });

                $sale->update([
                    'total_amount' => $totalAmount,
                    'total_cost' => $totalCost
                ]);

                // Reduce Stock for Sold Items
                foreach ($saleItems as $item) {
                    $item->product->decrement('quantity_in_stock', $item->quantity);
                }

                // Create Payments if not CREDIT (Unpaid)
                if ($sale->payment_status !== \App\Enums\PaymentStatus::CREDIT) {
                    \App\Models\Payment::factory()->create([
                        'sale_id' => $sale->id,
                        'customer_id' => $customer->id,
                        'amount' => $sale->payment_status === \App\Enums\PaymentStatus::PAID ? $totalAmount : $totalAmount / 2,
                        'payment_date' => $sale->sale_date,
                    ]);
                }
            }
        }
        
        // 6. Create some Walk-in Sales (No Customer)
        $walkInSales = \App\Models\Sale::factory(10)->create([
            'customer_id' => null,
            'payment_status' => \App\Enums\PaymentStatus::PAID, // Usually paid immediately
        ]);

        foreach ($walkInSales as $sale) {
             $saleItems = \App\Models\SaleItem::factory(rand(1, 3))->create([
                'sale_id' => $sale->id,
                'product_id' => $products->random()->id,
            ]);

            $totalAmount = $saleItems->sum(function($item) {
                 return $item->quantity * $item->unit_selling_price;
            });
            $totalCost = $saleItems->sum(function($item) {
                 return $item->quantity * $item->unit_cost_price;
            });

            $sale->update([
                'total_amount' => $totalAmount,
                'total_cost' => $totalCost
            ]);

            \App\Models\Payment::factory()->create([
                'sale_id' => $sale->id,
                'customer_id' => null,
                'amount' => $totalAmount,
                'payment_date' => $sale->sale_date,
            ]);
        }
    }
}
