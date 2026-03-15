<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;

class ManageBuyXGetYDiscount extends BaseEditRecord
{
    protected static string $resource = DiscountResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('admin::discount.pages.limitations.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::discount.pages.limitations.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::discount-limitations');
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function getRelationManagers(): array
    {
        return [
            RelationGroup::make('Limitations', [
                DiscountResource\RelationManagers\CollectionLimitationRelationManager::class,
                DiscountResource\RelationManagers\BrandLimitationRelationManager::class,
                DiscountResource\RelationManagers\ProductLimitationRelationManager::class,
                DiscountResource\RelationManagers\ProductVariantLimitationRelationManager::class,
            ]),

        ];
    }
}
