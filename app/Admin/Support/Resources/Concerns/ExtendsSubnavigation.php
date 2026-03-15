<?php

namespace App\Admin\Support\Resources\Concerns;

use Filament\Pages\Page;

trait ExtendsSubnavigation
{
    public static function getRecordSubNavigation(Page $page): array
    {
        $pages = self::callStaticStoreHook('extendSubNavigation', static::getDefaultSubnavigation());

        return $page->generateNavigationItems($pages);
    }

    protected static function getDefaultSubNavigation(): array
    {
        return [];
    }
}
