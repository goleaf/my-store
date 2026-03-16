<?php

namespace App\Filament\Resources\FeaturedCategoryResource\Pages;

use App\Filament\Resources\FeaturedCategoryResource;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;

class ListFeaturedCategories extends BaseListRecords
{
    protected static string $resource = FeaturedCategoryResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
