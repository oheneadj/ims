<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use App\Mail\LowStockAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckLowStock extends Command
{
    protected $signature = 'ims:check-low-stock';
    protected $description = 'Check for low stock products and send email alerts';

    public function handle()
    {
        $lowStockProducts = Product::query()
            ->where('quantity_in_stock', '<=', 5)
            ->get();

        if ($lowStockProducts->isNotEmpty()) {
            $admin = User::whereNotNull('email')->first();
            
            if ($admin) {
                Mail::to($admin->email)->send(new LowStockAlert($lowStockProducts));
                $this->info('Low stock alert email sent to ' . $admin->email);
            } else {
                $this->warn('No admin user found with an email address.');
            }
        } else {
            $this->info('No low stock products found.');
        }
    }
}
