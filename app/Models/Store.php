<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'logo',
        'banner',
        'description',
        'email',
        'phone',
        'address_line_1',
        'city',
        'state',
        'country',
        'opening_hours',
        'commission_rate',
        'rating_avg',
        'total_reviews',
        'is_verified',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'opening_hours' => 'json',
            'commission_rate' => 'decimal:2',
            'rating_avg' => 'decimal:2',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(Customer::class, 'owner_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
