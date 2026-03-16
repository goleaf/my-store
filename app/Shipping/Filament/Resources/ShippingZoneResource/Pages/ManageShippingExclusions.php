<?php

namespace App\Shipping\Filament\Resources\ShippingZoneResource\Pages;

use Filament\Schemas\Schema;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use App\Shipping\Filament\Resources\ShippingExclusionListResource;
use App\Shipping\Filament\Resources\ShippingZoneResource;
use Filament\Actions;

class ManageShippingExclusions extends ManageRelatedRecords
{
    protected static string $resource = ShippingZoneResource::class;

    protected static string $relationship = 'shippingExclusions';

    protected static ?string $recordTitle = 'name';

    public function getTitle(): string|Htmlable
    {
        return __('storepanel.shipping::relationmanagers.exclusions.title_plural');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::shipping-exclusion-lists');
    }

    public static function getNavigationLabel(): string
    {
        return __('storepanel.shipping::relationmanagers.exclusions.title_plural');
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table->columns(
            ShippingExclusionListResource::getTableColumns(),
        )->headerActions([
            Actions\AttachAction::make()
                ->color('primary')
                ->label(
                    __('storepanel.shipping::relationmanagers.exclusions.actions.attach.label')
                )
                ->preloadRecordSelect()
                ->recordTitleAttribute('name'),
        ])->actions([
            Actions\DetachAction::make('detach')
                ->label(
                    __('storepanel.shipping::relationmanagers.exclusions.actions.detach.label')
                ),

        ]);
    }
}
