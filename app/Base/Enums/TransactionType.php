<?php

namespace App\Base\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum TransactionType: string
{
    use TranslatesEnumLabels;

    case Refund = 'refund';
    case Intent = 'intent';
    case Capture = 'capture';

    public function translationKey(): string
    {
        return "admin::order.transactions.{$this->value}";
    }
}
