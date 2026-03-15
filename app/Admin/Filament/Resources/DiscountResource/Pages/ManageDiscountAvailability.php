<?php

namespace App\Admin\Filament\Resources\DiscountResource\Pages;

use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use App\Admin\Filament\Resources\DiscountResource;
use App\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use App\Admin\Support\Pages\BaseManageRelatedRecords;
use App\Admin\Support\RelationManagers\ChannelRelationManager;

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
