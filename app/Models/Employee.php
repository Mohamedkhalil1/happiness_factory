<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nickname',
        'address',
        'phone',
        'social_status',
        'national_id',
        'worked_date',
        'details',
        'category_id',
        'salary',
    ];
//
//    protected $casts = [
//        'worked_date' => 'date',
//    ];

    public function category()
    {
        $this->belongsTo(EmployeesCategory::class, 'category_id');
    }

    public function getAvatar(): string
    {
        if ($this->avatar) {
            return Storage::disk('files')->url($this->avatar);
        }
        return '';
    }

    public function scopeByNameNickName($query, $searchValue)
    {
        return $query->where('name', 'LIKE', '%' . $searchValue . '%')
            ->orWhere('nickname', 'LIKE', '%' . $searchValue . '%');
    }

    public function scopeBySocialStatus($query, $searchValue)
    {
        if ($searchValue) {
            return $query;
        }
        return $query->where('social_status', $searchValue);
    }
}
