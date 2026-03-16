<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Contracts\Product;
use App\Support\Resources\Pages\ManageUrlsRelatedRecords;

class ManageProductUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $model = Product::class;
}
