<?php

namespace App\Shipping\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum ShippingMethodChargeBy: string
{
    use TranslatesEnumLabels;

    case CartTotal = 'cart_total';
    case Weight = 'weight';

    public function translationKey(): string
    {
        return "admin.shipping::shippingmethod.form.charge_by.options.{$this->value}";
    }
}
