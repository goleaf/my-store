<?php

namespace App\Base\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum SavedPaymentMethodType: string
{
    use TranslatesEnumLabels;

    case Card = 'card';
    case Paypal = 'paypal';
    case Payoneer = 'payoneer';

    public function translationKey(): string
    {
        return "store::base.saved-payment-method-types.{$this->value}";
    }
}
