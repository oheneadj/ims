<?php

namespace App\Filament\Pages;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Flex;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Flex::make([
                            Select::make('date_range')
                                ->options([
                                    'today' => 'Today',
                                    'this_week' => 'This Week',
                                    'this_month' => 'This Month',
                                    'this_year' => 'This Year',
                                    'last_30_days' => 'Last 30 Days',
                                    'all_time' => 'All Time',
                                    'custom' => 'Custom Range',
                                ])
                                ->default('this_month')
                                ->reactive()
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state !== 'custom') {
                                        $set('start_date', null);
                                        $set('end_date', null);
                                    }
                                }),
                            
                            DatePicker::make('start_date')
                                ->visible(fn (Get $get) => $get('date_range') === 'custom'),
                            
                            DatePicker::make('end_date')
                                ->visible(fn (Get $get) => $get('date_range') === 'custom'),
                        ])
                        ->alignEnd(),
                    ]),
            ]);
    }
}
