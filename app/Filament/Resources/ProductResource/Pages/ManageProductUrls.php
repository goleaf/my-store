<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Store\Models\Contracts\Product as ProductContract;
use App\Support\Resources\Pages\ManageUrlsRelatedRecords;

class ManageProductUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $model = ProductContract::class;
}
