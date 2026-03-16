<?php

namespace App\Http\Requests\Filament\Shipping;

use App\Base\Enums\DeliverySlotDayType;
use App\Http\Requests\BaseRequest;
use App\Models\Store\Models\DeliveryZone;
use Illuminate\Validation\Rule;

class DeliverySlotRequest extends BaseRequest
{
    protected ?string $dayType = null;

    public function forDayType(?string $dayType): static
    {
        $this->dayType = $dayType;

        return $this;
    }

    public function rules(): array
    {
        $zoneExistsRule = Rule::exists((new DeliveryZone)->getTable(), 'id');
        $specificDateRules = ['nullable', 'date'];
        $dayOfWeekRules = ['nullable', 'integer', 'between:0,6'];

        if ($this->dayType === DeliverySlotDayType::Specific->value) {
            $specificDateRules[0] = 'required';
        }

        if ($this->dayType === DeliverySlotDayType::Recurring->value) {
            $dayOfWeekRules[0] = 'required';
        }

        return [
            'zone_id' => ['nullable', 'integer', $zoneExistsRule],
            'day_type' => ['required', Rule::in(array_map(static fn (DeliverySlotDayType $dayType): string => $dayType->value, DeliverySlotDayType::cases()))],
            'specific_date' => $specificDateRules,
            'day_of_week' => $dayOfWeekRules,
            'label' => ['required', 'string', 'max:100'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'cutoff_hours' => ['required', 'integer', 'min:0', 'max:255'],
            'fee' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:0'],
            'booked_count' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
