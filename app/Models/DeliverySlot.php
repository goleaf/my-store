<?php

namespace App\Models;

use App\Base\Enums\DeliverySlotDayType;
use App\Models\Store\Models\DeliveryZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            'day_type' => DeliverySlotDayType::class,
            'specific_date' => 'date',
            'fee' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class, 'zone_id');
    }
}
