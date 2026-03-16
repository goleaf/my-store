<?php

namespace App\Models\Store\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'name',
        'min_order',
        'delivery_fee',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'min_order' => 'decimal:4',
            'delivery_fee' => 'decimal:4',
        ];
    }
}
