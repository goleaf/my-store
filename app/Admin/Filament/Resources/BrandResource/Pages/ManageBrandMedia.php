<?php

namespace App\Admin\Filament\Resources\BrandResource\Pages;

use App\Admin\Filament\Resources\BrandResource;
use App\Admin\Support\Resources\Pages\ManageMediasRelatedRecords;

class ManageBrandMedia extends ManageMediasRelatedRecords
{
    protected static string $resource = BrandResource::class;
}
