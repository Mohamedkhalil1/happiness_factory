<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Observers\PurchaseObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'provider_id',
        'date',
        'amount',
        'quantity',
        'total_amount',
        'paid_amount',
        'remain',
        'status',
        'color',
        'height',
        'width',
        'weight',
    ];

    protected static function booted()
    {
        self::observe(PurchaseObserver::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    public function getStatus()
    {
        if (!$this->remain) {
            $this->status = OrderStatus::DONE;
        } else if ($this->paid_amount) {
            $this->status = OrderStatus::IN_PROGRESS;
        } else {
            $this->status = OrderStatus::PENDING;
        }
    }

}
