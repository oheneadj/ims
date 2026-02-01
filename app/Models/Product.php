<?php

namespace App\Models;

use App\Enums\ProductMaterial;
use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'material',
        'description',
        'sku',
        'cost_price',
        'selling_price',
        'quantity_in_stock',
        'photo',
    ];

    protected $casts = [
        'type' => ProductType::class,
        'material' => ProductMaterial::class,
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity_in_stock' => 'integer',
    ];

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getProfitAttribute()
    {
        return $this->selling_price - $this->cost_price;
    }
}
