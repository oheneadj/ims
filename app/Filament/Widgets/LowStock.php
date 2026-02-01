<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStock extends TableWidget
{
    protected static ?int $sort = 6;
    
    protected int | string | array $columnSpan = '1/2';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Product::query()->where('quantity_in_stock', '<=', 5))
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('quantity_in_stock')
                    ->label('Stock')
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('selling_price')
                    ->money('GHS'),
            ])
            ->actions([
                Actions\Action::make('Edit')
                    ->url(fn (Product $record): string => \App\Filament\Resources\Products\ProductResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
