<?php

namespace App\Base\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum HomeBannerType: string
{
    use TranslatesEnumLabels;

    case Top = 'top';
    case Middle = 'middle';
    case Bottom = 'bottom';

    public function translationKey(): string
    {
        return "admin::global.enums.home_banner_type.{$this->value}";
    }
}
