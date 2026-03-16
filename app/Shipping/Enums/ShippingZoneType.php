<?php

namespace App\Shipping\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum ShippingZoneType: string
{
    use TranslatesEnumLabels;

    case Unrestricted = 'unrestricted';
    case Countries = 'countries';
    case States = 'states';
    case Postcodes = 'postcodes';

    public function translationKey(): string
    {
        return "admin.shipping::shippingzone.form.type.options.{$this->value}";
    }
}
