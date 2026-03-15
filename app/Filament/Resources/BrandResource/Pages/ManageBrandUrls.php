<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use App\Store\Models\Contracts\Brand as BrandContract;
use App\Support\Resources\Pages\ManageUrlsRelatedRecords;

class ManageBrandUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = BrandResource::class;

    protected static string $model = BrandContract::class;
}
