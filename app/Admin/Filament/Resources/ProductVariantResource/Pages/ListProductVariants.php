<?php

namespace App\Admin\Filament\Resources\ProductVariantResource\Pages;

use App\Admin\Filament\Resources\ProductVariantResource;
use App\Admin\Support\Pages\BaseListRecords;

class ListProductVariants extends BaseListRecords
{
    protected static string $resource = ProductVariantResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    public static function createActionFormInputs(): array
    {
        return [];
    }

    public function getDefaultTabs(): array
    {
        return [];
    }
}
