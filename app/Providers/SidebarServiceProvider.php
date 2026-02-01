<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SidebarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('components.layouts.sidebar', function ($view) {
            $view->with([
                'lowStockCount' => Product::where('quantity_in_stock', '<=', 5)->count(),
                'totalCustomers' => Customer::count(),
                'todaySales' => '₵' . number_format(Sale::whereDate('sale_date', today())->sum('total_amount'), 0),
                'pendingPayments' => Sale::where('payment_status', '!=', 'paid')->count(),
            ]);
        });
    }
}
