<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use App\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use App\Support\Pages\BaseManageRelatedRecords;
use App\Support\RelationManagers\ChannelRelationManager;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;

class ManageDiscountAvailability extends BaseManageRelatedRecords
{
    protected static string $resource = DiscountResource::class;

    protected static string $relationship = 'channels';

    public function getTitle(): string
    {
        return __('admin::discount.pages.availability.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::availability');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::discount.pages.availability.label');
    }

    public function getRelationManagers(): array
    {
        return [
            RelationGroup::make('Availability', [
                ChannelRelationManager::class,
                CustomerGroupRelationManager::make([
                    'pivots' => [
                        'enabled',
                        'visible',
                    ],
                ]),
            ]),
        ];
    }
}
