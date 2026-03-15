<?php

namespace App\Admin\Filament\Resources\ProductResource\Pages;

use App\Admin\Filament\Resources\ProductResource;
use App\Admin\Support\Resources\Pages\ManageMediasRelatedRecords;

class ManageProductMedia extends ManageMediasRelatedRecords
{
    protected static string $resource = ProductResource::class;
}
