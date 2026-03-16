<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use App\Filament\Resources\CollectionResource;
use App\Models\Contracts\Collection;
use App\Support\Resources\Pages\ManageUrlsRelatedRecords;

class ManageCollectionUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = CollectionResource::class;

    protected static string $model = Collection::class;

    public function getBreadcrumbs(): array
    {
        $crumbs = static::getResource()::getCollectionBreadcrumbs($this->getRecord());

        $crumbs[] = $this->getBreadcrumb();

        return $crumbs;
    }
}
