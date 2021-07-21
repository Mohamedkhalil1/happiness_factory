<?php

namespace App\Models;

use App\Observers\OrderObserver;
use App\Observers\TransferObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'note',
        'purchase_id',
    ];

    protected static function booted()
    {
        self::observe(TransferObserver::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
