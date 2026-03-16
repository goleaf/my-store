<?php

namespace App\Base\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum ProductStatus: string
{
    use TranslatesEnumLabels;

    case Draft = 'draft';
    case Published = 'published';

    public function translationKey(): string
    {
        return "admin::product.table.status.states.{$this->value}";
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'warning',
            self::Published => 'success',
        };
    }
}
