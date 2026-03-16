<?php

namespace App\Base\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum ProductVariantPurchasable: string
{
    use TranslatesEnumLabels;

    case Always = 'always';
    case InStock = 'in_stock';
    case InStockOrOnBackorder = 'in_stock_or_on_backorder';

    public function translationKey(): string
    {
        return "admin::productvariant.form.purchasable.options.{$this->value}";
    }
}
