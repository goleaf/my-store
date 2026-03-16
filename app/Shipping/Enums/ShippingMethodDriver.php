<?php

namespace App\Shipping\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum ShippingMethodDriver: string
{
    use TranslatesEnumLabels;

    case ShipBy = 'ship-by';
    case Collection = 'collection';

    public function translationKey(): string
    {
        return "admin.shipping::shippingmethod.form.driver.options.{$this->value}";
    }
}
