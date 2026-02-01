<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make('Basic Details')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU')
                                    ->maxLength(50),
                                Forms\Components\Select::make('type')
                                    ->options(\App\Enums\ProductType::class)
                                    ->required(),
                                Forms\Components\Select::make('material')
                                    ->options(\App\Enums\ProductMaterial::class),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpanFull(),
                            ])->columns(2),

                         Schemas\Components\Section::make('Pricing & Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('cost_price')
                                    ->label('Cost Price')
                                    ->numeric()
                                    ->prefix('GH₵')
                                    ->required(),
                                Forms\Components\TextInput::make('selling_price')
                                    ->label('Selling Price')
                                    ->numeric()
                                    ->prefix('GH₵')
                                    ->required(),
                                Forms\Components\TextInput::make('quantity_in_stock')
                                    ->label('Stock Quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(0),
                            ])->columns(3),
                    ])->columnSpan(2),

                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Section::make('Financials')
                            ->schema([
                                Forms\Components\FileUpload::make('photo')
                                    ->image()
                                    ->directory('products')
                                    ->visibility('public'),
                            ]),
                    ])->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('selling_price')
                    ->money('GHS')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_in_stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => $state <= 5 ? 'danger' : 'success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(\App\Enums\ProductType::class),
                Tables\Filters\TernaryFilter::make('low_stock')
                     ->label('Low Stock')
                     ->queries(
                        true: fn ($query) => $query->where('quantity_in_stock', '<=', 5),
                        false: fn ($query) => $query->where('quantity_in_stock', '>', 5),
                     ),
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StockMovementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
