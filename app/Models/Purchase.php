<?php

namespace App\Models;

use App\Observers\PurchaseObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'ore_id',
        'provider_id',
        'date',
        'amount',
        'quantity',
        'total_amount',
        'paid_amount',
        'remain',
        'status',
    ];

    protected static function booted()
    {
        self::observe(PurchaseObserver::class);
    }

    public function ore(): BelongsTo
    {
        return $this->belongsTo(Ore::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
