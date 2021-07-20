<?php

namespace App\Models;

use App\Http\Livewire\Materials\Materials;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ore extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'height',
        'width',
        'weight',
        'quantity',
        'material_id'
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
