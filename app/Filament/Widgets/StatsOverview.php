<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class StatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startDate = match ($this->filters['date_range'] ?? 'this_month') {
            'today' => now()->startOfDay(),
            'this_week' => now()->startOfWeek(),
            'this_month' => now()->startOfMonth(),
            'this_year' => now()->startOfYear(),
            'last_30_days' => now()->subDays(30),
            'custom' => Carbon::parse($this->filters['start_date'] ?? now()),
            'all_time' => null,
            default => now()->startOfMonth(),
        };

        $endDate = match ($this->filters['date_range'] ?? 'this_month') {
            'custom' => Carbon::parse($this->filters['end_date'] ?? now()),
            default => now()->endOfDay(),
        };

        // 1. Total Revenue & Trend (Filtered)
        $currentRevenueQuery = \App\Models\Sale::query();
        $expensesQuery = \App\Models\Expense::query();
        $salesCountQuery = \App\Models\Sale::query();
        $customersQuery = \App\Models\Customer::query();

        if ($startDate) {
            $currentRevenueQuery->whereBetween('sale_date', [$startDate, $endDate]);
            $expensesQuery->whereBetween('expense_date', [$startDate, $endDate]);
            $salesCountQuery->whereBetween('sale_date', [$startDate, $endDate]);
            $customersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $currentRevenue = $currentRevenueQuery->sum('total_amount');
        $expenses = $expensesQuery->sum('amount');
        $salesCount = $salesCountQuery->count();
        $newCustomers = $customersQuery->count();
        
        // Debt is typically a breakdown, but here we can show total debt of ALL time, 
        // or we could show "Credit Sales" in this period. 
        // User asked for "Debt", usually implies "Current Outstanding Debt".
        // Let's keep Debt as "All Time" outstanding, but label it clearly.
        $totalDebt = \App\Models\Customer::sum('current_balance');

        return [
            Stat::make('Total Revenue', 'GH₵ ' . number_format($currentRevenue, 2))
                ->description('Revenue in selected period')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Total Expenses', 'GH₵ ' . number_format($expenses, 2))
                ->description('Expenses in selected period')
                ->descriptionIcon('heroicon-m-receipt-refund')
                ->color('danger'),
            
            Stat::make('Total Sales Made', $salesCount)
                ->description('Transactions in period')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('New Customers', $newCustomers)
                ->description('Joined in period')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Outstanding Debt', 'GH₵ ' . number_format($totalDebt, 2))
                ->description('Total current customer debt (All Time)')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning'),
        ];
    }
}
