<?php

namespace App\Base\Enums;

use App\Base\Enums\Concerns\ProvidesProductAssociationType;

enum ProductAssociation: string implements ProvidesProductAssociationType
{
    case CROSS_SELL = 'cross-sell';
    case UP_SELL = 'up-sell';
    case ALTERNATE = 'alternate';

    public function label(): string
    {
        return match ($this) {
            self::CROSS_SELL => __('store::base.product-association-types.cross-sell'),
            self::UP_SELL => __('store::base.product-association-types.up-sell'),
            self::ALTERNATE => __('store::base.product-association-types.alternate'),
        };
    }
}
