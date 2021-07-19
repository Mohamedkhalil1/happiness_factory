<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'address',
        'amount_before_discount',
        'discount',
        'amount_after_discount',
        'remain',
        'status',
        'client_id',
    ];

    public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(Inventory::class)->withPivot(['quantity', 'price']);
    }

    public function orderInventories(): HasMany
    {
        return $this->hasMany(inventoryOrder::class);
    }
}
