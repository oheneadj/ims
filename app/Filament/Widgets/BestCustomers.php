<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class BestCustomers extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = '1/2';

    public function table(Table $table): Table
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

        return $table
            ->query(
                Customer::query()
                    ->withSum(['sales' => function ($query) use ($startDate, $endDate) {
                        if ($startDate) {
                            $query->whereBetween('sale_date', [$startDate, $endDate]);
                        }
                    }], 'total_amount')
                    ->orderByDesc('sales_sum_total_amount')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('sales_sum_total_amount')
                    ->label('Total Spent')
                    ->money('GHS')
                    ->sortable(),
            ]);
    }
}
