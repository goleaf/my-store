<?php

namespace App\Base\Enums;

use App\Base\Enums\Concerns\TranslatesEnumLabels;

enum HomeSectionType: string
{
    use TranslatesEnumLabels;

    case ProductGrid = 'product_grid';
    case SidebarGrid = 'sidebar_grid';
    case FeaturedItems = 'featured_items';

    public function translationKey(): string
    {
        return "admin::global.enums.home_section_type.{$this->value}";
    }
}
