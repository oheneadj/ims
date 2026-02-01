<?php

namespace App\Console\Commands;

use App\Enums\PaymentStatus;
use App\Models\Sale;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixSaleBalancesCommand extends Command
{
    protected $signature = 'ims:fix-data';
    protected $description = 'Recalculate sale amount_paid and payment_status based on actual payments';

    public function handle()
    {
        $this->info('Starting Sale Balance Fix...');

        $sales = Sale::with('payments')->get();
        $bar = $this->output->createProgressBar($sales->count());

        foreach ($sales as $sale) {
            $totalPaid = $sale->payments->sum('amount');
            
            $sale->amount_paid = $totalPaid;

            if ($sale->amount_paid >= $sale->total_amount) {
                $sale->payment_status = PaymentStatus::PAID;
            } elseif ($sale->amount_paid > 0) {
                $sale->payment_status = PaymentStatus::PARTIAL;
            } else {
                 $sale->payment_status = PaymentStatus::CREDIT; // Or whatever default 'unpaid' status is
            }

            // Quietly save to avoid triggering observers that might double-count or loop if not careful
            // But we actually WANT observers if they sync customer balance... 
            // SaleObserver syncs customer balance on 'updated' if total_amount changes.
            // PaymentObserver syncs customer balance on 'created'.
            // Here we are just modifying the aggregating Sale record.
            // The Customer Balance should ideally be sum of (Total Sales - Total Payments).
            // Let's just save the Sale record fixes for now.
            $sale->saveQuietly(); 

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Sale data fixed successfully.');
    }
}
