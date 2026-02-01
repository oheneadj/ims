<?php

namespace App\Observers;

use App\Models\SaleItem;
use App\Models\StockMovement;

class SaleItemObserver
{
    public function created(SaleItem $saleItem): void
    {
        // Decrease stock
        $saleItem->product->decrement('quantity_in_stock', $saleItem->quantity);

        // Record movement
        StockMovement::create([
            'product_id' => $saleItem->product_id,
            'type' => 'sale',
            'quantity' => -1 * $saleItem->quantity,
            'reference' => 'Sale #' . $saleItem->sale_id,
        ]);
    }

    public function deleted(SaleItem $saleItem): void
    {
        // Increase stock (Reverse sale)
        $saleItem->product->increment('quantity_in_stock', $saleItem->quantity);

        StockMovement::create([
            'product_id' => $saleItem->product_id,
            'type' => 'return', // or sale_deletion
            'quantity' => $saleItem->quantity,
            'reference' => 'Sale Reversal #' . $saleItem->sale_id,
        ]);
    }
}
