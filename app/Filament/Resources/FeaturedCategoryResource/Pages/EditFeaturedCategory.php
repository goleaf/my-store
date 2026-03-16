<?php

namespace App\Filament\Resources\FeaturedCategoryResource\Pages;

use App\Filament\Resources\FeaturedCategoryResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions;

class EditFeaturedCategory extends BaseEditRecord
{
    protected static string $resource = FeaturedCategoryResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
