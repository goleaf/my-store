<?php

namespace App\Filament\Resources\HomeBannerResource\Pages;

use App\Filament\Resources\HomeBannerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomeBanner extends EditRecord
{
    protected static string $resource = \App\Filament\Resources\HomeBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
