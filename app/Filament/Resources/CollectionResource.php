<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionResource\Pages;
use App\Store\Models\Contracts\Collection as CollectionContract;
use App\Support\Forms\Components\Attributes;
use App\Support\Resources\BaseResource;
use Filament\Forms\Components\Component;
use Filament\Pages\Enums\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CollectionResource extends BaseResource
{
    protected static ?string $permission = 'catalog:manage-collections';

    protected static ?string $model = CollectionContract::class;

    protected static int $globalSearchResultsLimit = 5;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getLabel(): string
    {
        return __('admin::collection.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::collection.plural_label');
    }

    public static function getNavigationItems(): array
    {
        return [];
    }

    public static function getCollectionBreadcrumbs(CollectionContract $collection): array
    {
        $crumbs = [
            CollectionGroupResource::getUrl('index') => CollectionGroupResource::getPluralLabel(),
            CollectionGroupResource::getUrl('edit', [
                'record' => $collection->group,
            ]) => $collection->group->name,
        ];

        foreach ($collection->ancestors()->defaultOrder()->get() as $childCollection) {
            $crumbs[
            CollectionResource::getUrl('edit', [
                'record' => $childCollection,
            ])
            ] = $childCollection->attr('name');
        }

        $crumbs[
        static::getUrl('edit', [
            'record' => $collection,
        ])] = $collection->attr('name');

        return $crumbs;
    }

    public static function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                static::getAttributeDataFormComponent(),
            ])
            ->columns(1);
    }

    protected static function getAttributeDataFormComponent(): Component
    {
        return Attributes::make();
    }

    protected static function getMainFormComponents(): array
    {
        return [
        ];
    }

    protected static function getDefaultRelations(): array
    {
        return [];
    }

    public static function getDefaultSubNavigation(): array
    {
        return [
            CollectionResource\Pages\EditCollection::class,
            CollectionResource\Pages\ManageCollectionChildren::class,
            CollectionResource\Pages\ManageCollectionProducts::class,
            CollectionResource\Pages\ManageCollectionAvailability::class,
            CollectionResource\Pages\ManageCollectionMedia::class,
            CollectionResource\Pages\ManageCollectionUrls::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => CollectionResource\Pages\ListCollections::route('/'),
            'availability' => CollectionResource\Pages\ManageCollectionAvailability::route('/{record}/availability'),
            'children' => CollectionResource\Pages\ManageCollectionChildren::route('/{record}/children'),
            'products' => CollectionResource\Pages\ManageCollectionProducts::route('/{record}/products'),
            'edit' => CollectionResource\Pages\EditCollection::route('/{record}/edit'),
            'media' => CollectionResource\Pages\ManageCollectionMedia::route('/{record}/media'),
            'urls' => CollectionResource\Pages\ManageCollectionUrls::route('/{record}/urls'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->translateAttribute('name');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'group.name', // Needed to trig canGloballySearch()
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with([
            'group',
        ]);
    }
}
