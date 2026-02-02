<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
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
        'name',
        'phone',
        'email',
        'address',
        'is_credit_customer',
        'credit_limit',
        'current_balance', // CAUTION: Should be updated via Observers/transactions, not manually editable often
        'notes',
    ];

    protected $casts = [
        'is_credit_customer' => 'boolean',
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - $this->current_balance;
    }
}
