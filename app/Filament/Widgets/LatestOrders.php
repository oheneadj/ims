<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Widgets\TableWidget as BaseWidget;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class LatestOrders extends BaseWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 10;

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
                Sale::query()
                    ->when($startDate, fn ($query) => $query->whereBetween('sale_date', [$startDate, $endDate]))
                    ->latest('sale_date')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('GHS')
                    ->sortable(),
            ])
            ->actions([
                Action::make('open')
                    ->url(fn (Sale $record): string => route('filament.admin.resources.sales.edit', $record)),
            ]);
    }
}
