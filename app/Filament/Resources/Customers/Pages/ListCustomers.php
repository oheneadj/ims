<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'owing' => Tab::make('Owing')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('current_balance', '>', 0)),
            'paid_up' => Tab::make('Paid Up')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('current_balance', '<=', 0)),
        ];
    }
}
