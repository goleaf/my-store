<?php

namespace App\Models\Store\Models;

use Database\Factories\DeliveryZoneFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory;

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

    protected static function newFactory(): DeliveryZoneFactory
    {
        return DeliveryZoneFactory::new();
    }
}
