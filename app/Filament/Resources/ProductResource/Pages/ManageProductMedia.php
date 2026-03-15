<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Support\Resources\Pages\ManageMediasRelatedRecords;

class ManageProductMedia extends ManageMediasRelatedRecords
{
    protected static string $resource = ProductResource::class;
}
