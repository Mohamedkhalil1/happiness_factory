<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'weight',
        'height',
        'quantity',
        'amount',
        'provider_id',
        'note',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
