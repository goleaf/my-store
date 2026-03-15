<?php

namespace App\Admin\Filament\Resources\CollectionResource\Pages;

use App\Admin\Filament\Resources\CollectionResource;
use App\Admin\Support\Resources\Pages\ManageUrlsRelatedRecords;
use App\Store\Models\Contracts\Collection as CollectionContract;

class ManageCollectionUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = CollectionResource::class;

    protected static string $model = CollectionContract::class;

    public function getBreadcrumbs(): array
    {
        $crumbs = static::getResource()::getCollectionBreadcrumbs($this->getRecord());

        $crumbs[] = $this->getBreadcrumb();

        return $crumbs;
    }
}
