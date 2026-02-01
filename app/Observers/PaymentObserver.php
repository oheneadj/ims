<?php

namespace App\Observers;

use App\Enums\PaymentStatus;
use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        // Decrease customer balance (they paid)
        if ($payment->customer_id) {
            $payment->customer->decrement('current_balance', $payment->amount);
        }

        // Update Sale amount_paid
        if ($payment->sale_id && $payment->sale) {
            $sale = $payment->sale;
            $sale->increment('amount_paid', $payment->amount);

            // Update status
            if ($sale->amount_paid >= $sale->total_amount) {
                $sale->update(['payment_status' => PaymentStatus::PAID]);
            } elseif ($sale->amount_paid > 0) {
                $sale->update(['payment_status' => PaymentStatus::PARTIAL]);
            }
        }
    }

    public function deleted(Payment $payment): void
    {
        // Revert balance
        if ($payment->customer_id) {
            $payment->customer->increment('current_balance', $payment->amount);
        }

        // Revert Sale
        if ($payment->sale_id && $payment->sale) {
            $sale = $payment->sale;
            $sale->decrement('amount_paid', $payment->amount);

             // Re-evaluate status
             if ($sale->amount_paid >= $sale->total_amount) {
                $sale->update(['payment_status' => PaymentStatus::PAID]);
            } elseif ($sale->amount_paid > 0) {
                $sale->update(['payment_status' => PaymentStatus::PARTIAL]);
            } else {
                $sale->update(['payment_status' => PaymentStatus::CREDIT]);
            }
        }
    }
}
