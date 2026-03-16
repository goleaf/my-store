<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverySlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'day_type',
        'specific_date',
        'day_of_week',
        'label',
        'start_time',
        'end_time',
        'cutoff_hours',
        'fee',
        'capacity',
        'booked_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'specific_date' => 'date',
            'fee' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function zone()
    {
        return $this->belongsTo(\App\Models\Store\Models\DeliveryZone::class, 'zone_id');
    }
}
