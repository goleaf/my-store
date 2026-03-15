<?php

namespace App\Admin\Filament\Resources\ProductResource\Pages;

use App\Admin\Filament\Resources\ProductResource;
use App\Admin\Support\Resources\Pages\ManageUrlsRelatedRecords;
use App\Store\Models\Contracts\Product as ProductContract;

class ManageProductUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $model = ProductContract::class;
}
