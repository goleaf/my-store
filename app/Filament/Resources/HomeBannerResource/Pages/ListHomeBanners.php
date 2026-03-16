<?php

namespace App\Filament\Resources\HomeBannerResource\Pages;

use App\Filament\Resources\HomeBannerResource;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;

class ListHomeBanners extends BaseListRecords
{
    protected static string $resource = HomeBannerResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
