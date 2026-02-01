<?php

namespace App\Observers;

use App\Models\Sale;

class SaleObserver
{
    public function created(Sale $sale): void
    {
        if ($sale->customer_id) {
            $sale->customer->increment('current_balance', $sale->total_amount);
        }
    }

    public function updated(Sale $sale): void
    {
        if ($sale->isDirty('total_amount') && $sale->customer_id) {
            $diff = $sale->total_amount - $sale->getOriginal('total_amount');
            $sale->customer->increment('current_balance', $diff);
        }
    }

    public function deleted(Sale $sale): void
    {
        if ($sale->customer_id) {
            $sale->customer->decrement('current_balance', $sale->total_amount);
        }
    }
}
