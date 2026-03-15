<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use App\Support\Resources\Pages\ManageMediasRelatedRecords;

class ManageBrandMedia extends ManageMediasRelatedRecords
{
    protected static string $resource = BrandResource::class;
}
