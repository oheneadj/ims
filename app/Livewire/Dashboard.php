<?php

namespace App\Livewire;

use App\Enums\PaymentStatus;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    // Stats
    public $todaySales;
    public $monthSales;
    public $monthOrders;
    public $salesGrowth;

    public $todayRevenue;
    public $monthRevenue;
    public $revenueGrowth;

    public $monthExpenses;
    public $expenseGrowth;

    public $netProfit;

    public $totalCustomers;
    public $customerGrowth;

    // Charts
    public $revenueChartData = [];
    public $customerChartData = [];
    public $revenueChartCategories = [];

    // Widgets
    public $lowStockProducts;
    public $recentSales;
    public $recentPayments;
    public $topProducts;
    public $topCustomers;
    
    // New Chart Data
    public $weeklySalesData = [];
    public $weeklySalesLabels = [];
    public $revenueLastYearData = [];
    public $orderByCategoryData = [];
    public $orderByCategoryLabels = [];

    public function mount()
    {
        $now = now();

        // 1. Sales Stats & Growth
        $this->todaySales = Sale::whereDate('sale_date', $now->today())->sum('total_amount');
        $this->monthSales = Sale::whereYear('sale_date', $now->year)
                                ->whereMonth('sale_date', $now->month)
                                ->sum('total_amount');
        
        $this->monthOrders = Sale::whereYear('sale_date', $now->year)
                                ->whereMonth('sale_date', $now->month)
                                ->count();

        $lastMonthSales = Sale::whereYear('sale_date', $now->copy()->subMonth()->year)
                              ->whereMonth('sale_date', $now->copy()->subMonth()->month)
                              ->sum('total_amount');
        $this->salesGrowth = $this->calculateGrowth($lastMonthSales, $this->monthSales);


        // 2. Revenue Stats & Growth
        $this->todayRevenue = Payment::whereDate('payment_date', $now->today())->sum('amount');
        $this->monthRevenue = Payment::whereYear('payment_date', $now->year)
                                     ->whereMonth('payment_date', $now->month)
                                     ->sum('amount');

        $lastMonthRevenue = Payment::whereYear('payment_date', $now->copy()->subMonth()->year)
                                   ->whereMonth('payment_date', $now->copy()->subMonth()->month)
                                   ->sum('amount');
        $this->revenueGrowth = $this->calculateGrowth($lastMonthRevenue, $this->monthRevenue);


        // 3. Expenses Stats & Growth
        $this->monthExpenses = Expense::whereYear('expense_date', $now->year)
                                      ->whereMonth('expense_date', $now->month)
                                      ->sum('amount');
        $lastMonthExpenses = Expense::whereYear('expense_date', $now->copy()->subMonth()->year)
                                    ->whereMonth('expense_date', $now->copy()->subMonth()->month)
                                    ->sum('amount');
        $this->expenseGrowth = $this->calculateGrowth($lastMonthExpenses, $this->monthExpenses);


        // 4. Customer Stats
        $this->totalCustomers = \App\Models\Customer::count();
        $currentMonthCustomers = \App\Models\Customer::whereYear('created_at', $now->year)
                                                    ->whereMonth('created_at', $now->month)
                                                    ->count();
        $lastMonthCustomers = \App\Models\Customer::whereYear('created_at', $now->copy()->subMonth()->year)
                                                  ->whereMonth('created_at', $now->copy()->subMonth()->month)
                                                  ->count();
        $this->customerGrowth = $this->calculateGrowth($lastMonthCustomers, $currentMonthCustomers);


        // 5. Net Profit (Cash Flow)
        $this->netProfit = $this->monthRevenue - $this->monthExpenses;


        // 6. Chart Data (Current Year Monthly)
        $months = collect(range(1, 12));
        $this->revenueChartCategories = $months->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('M'))->toArray();

        // Revenue Chart (Cash collected)
        $this->revenueChartData = $months->map(function ($month) use ($now) {
            return Payment::whereYear('payment_date', $now->year)
                          ->whereMonth('payment_date', $month)
                          ->sum('amount');
        })->toArray();

        // Customer Acquisition Chart
        $this->customerChartData = $months->map(function ($month) use ($now) {
            return \App\Models\Customer::whereYear('created_at', $now->year)
                                      ->whereMonth('created_at', $month)
                                      ->count();
        })->toArray();


        // 7. Recent Sales
        $this->recentSales = Sale::with('customer')
                                 ->latest('sale_date')
                                 ->take(5)
                                 ->get();

        // 8. Low Stock
        $this->lowStockProducts = Product::where('quantity_in_stock', '<=', 5)
                                         ->orderBy('quantity_in_stock', 'asc')
                                         ->take(5)
                                         ->get();

        // 9. Top Products (Total Earning Widget)
        // Note: Using a simplified query. For heavy loads, this should be cached or optimized.
        $this->topProducts = Product::withCount(['saleItems as sold_count' => function($query) {
                                            $query->select(DB::raw('sum(quantity)'));
                                        }])
                                        ->orderByDesc('sold_count')
                                        ->take(3)
                                        ->get();

        // 10. Weekly Earning Report (Last 7 Days)
        $last7Days = collect(range(6, 0))->map(fn($days) => $now->copy()->subDays($days));
        $this->weeklySalesLabels = $last7Days->map(fn($d) => $d->format('D'))->toArray();
        $this->weeklySalesData = $last7Days->map(function($date) {
            return Sale::whereDate('sale_date', $date)->sum('total_amount');
        })->toArray();

        // 11. Yearly Revenue Comparison
        $this->revenueLastYearData = $months->map(function ($month) use ($now) {
            return Payment::whereYear('payment_date', $now->copy()->subYear()->year)
                          ->whereMonth('payment_date', $month)
                          ->sum('amount');
        })->toArray();

        // 12. Recent Payments (Transactions Widget)
        $this->recentPayments = Payment::with('customer')
                                       ->latest('payment_date')
                                       ->take(4)
                                       ->get();

        // 13. Order Statistics (By Product Type)
        // Groups sales items by product type to approximate categories
        $stats = DB::table('sale_items')
                   ->join('products', 'sale_items.product_id', '=', 'products.id')
                   ->select('products.type', DB::raw('sum(sale_items.quantity) as total_qty'))
                   ->groupBy('products.type')
                   ->get();
        
        $this->orderByCategoryLabels = $stats->pluck('type')->toArray();
        $this->orderByCategoryData = $stats->pluck('total_qty')->toArray();

        // 14. Top Customers by Amount Spent
        $this->topCustomers = \App\Models\Customer::select('customers.*')
            ->selectRaw('COALESCE(SUM(sales.total_amount), 0) as total_spent')
            ->leftJoin('sales', 'customers.id', '=', 'sales.customer_id')
            ->groupBy('customers.id')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();
    }

    private function calculateGrowth($previous, $current)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
