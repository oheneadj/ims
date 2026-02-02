<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory, SoftDeletes, \Spatie\Activitylog\Traits\LogsActivity;

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'customer_id',
        'sale_date',
        'total_amount',
        'total_cost',
        'discount',
        'amount_paid',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'payment_status' => PaymentStatus::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
