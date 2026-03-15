<?php

namespace App\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use App\Admin\Filament\Resources\ProductResource;
use App\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use App\Admin\Support\Pages\BaseManageRelatedRecords;
use App\Admin\Support\RelationManagers\ChannelRelationManager;

class ManageProductAvailability extends BaseManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'channels';

    public function getTitle(): string
    {

        return __('admin::product.pages.availability.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::availability');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::product.pages.availability.label');
    }

    public function getRelationManagers(): array
    {
        return [
            RelationGroup::make('Availability', [
                ChannelRelationManager::class,
                CustomerGroupRelationManager::class,
            ]),
        ];
    }
}
