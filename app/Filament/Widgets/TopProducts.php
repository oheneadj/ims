<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class TopProducts extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3; // Row 2
    
    protected int | string | array $columnSpan = '1/2'; // Share Row

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
                Product::query()
                    ->withCount(['saleItems' => function ($query) use ($startDate, $endDate) {
                        if ($startDate) {
                            $query->whereHas('sale', function ($q) use ($startDate, $endDate) {
                                $q->whereBetween('sale_date', [$startDate, $endDate]);
                            });
                        }
                    }])
                    ->orderByDesc('sale_items_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_items_count')
                    ->label('Units Sold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_in_stock')
                    ->label('Stock Left')
                    ->sortable()
                    ->color(fn (string $state): string => $state <= 5 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('price')
                    ->money('GHS')
                    ->sortable(),
            ]);
    }
}
