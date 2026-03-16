<?php

namespace App\Base\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum DeliverySlotDayType: string
{
    use TranslatesEnumLabels;

    case Specific = 'specific';
    case Recurring = 'recurring';

    public function translationKey(): string
    {
        return "admin::global.enums.delivery_slot_day_type.{$this->value}";
    }
}
