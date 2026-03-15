<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductVariantResource;
use App\Support\Pages\BaseListRecords;

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
