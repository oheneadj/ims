<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = '1/2';

    protected ?string $heading = 'Sales Chart';

    protected function getData(): array
    {
        $startDate = match ($this->filters['date_range'] ?? 'this_month') {
            'today' => now()->startOfDay(),
            'this_week' => now()->startOfWeek(),
            'this_month' => now()->startOfMonth(),
            'this_year' => now()->startOfYear(),
            'last_30_days' => now()->subDays(30),
            'custom' => Carbon::parse($this->filters['start_date'] ?? now()),
            'all_time' => Carbon::parse('2000-01-01'), // Long ago
            default => now()->startOfMonth(),
        };

        $endDate = match ($this->filters['date_range'] ?? 'this_month') {
            'custom' => Carbon::parse($this->filters['end_date'] ?? now()),
            default => now()->endOfDay(),
        };

        $data = \App\Models\Sale::query()
            ->selectRaw('DATE(sale_date) as date, SUM(total_amount) as total')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        $labels = [];
        $values = [];
        
        // Populate labels based on date range
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $formattedDate;
            $values[] = $data[$formattedDate] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales (GH₵)',
                    'data' => $values,
                    'fill' => 'start',
                    'borderColor' => '#F59E0B', // Amber-500
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
