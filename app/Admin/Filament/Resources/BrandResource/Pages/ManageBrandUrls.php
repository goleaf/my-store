<?php

namespace App\Admin\Filament\Resources\BrandResource\Pages;

use App\Admin\Filament\Resources\BrandResource;
use App\Admin\Support\Resources\Pages\ManageUrlsRelatedRecords;
use App\Store\Models\Contracts\Brand as BrandContract;

class ManageBrandUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = BrandResource::class;

    protected static string $model = BrandContract::class;
}
