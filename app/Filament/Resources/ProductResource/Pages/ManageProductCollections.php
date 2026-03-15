<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Events\ProductCollectionsUpdated;
use App\Filament\Resources\ProductResource;
use App\Store\Models\Contracts\Collection as CollectionContract;
use App\Support\Pages\BaseManageRelatedRecords;
use App\Support\Tables\Columns\TranslatedTextColumn;
use Filament\Forms;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class ManageProductCollections extends BaseManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'collections';

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::collections');
    }

    public function getTitle(): string
    {
        return __('admin::product.pages.collections.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::product.pages.collections.label');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable('position')
            ->columns([
                TranslatedTextColumn::make('attribute_data.name')
                    ->description(fn (CollectionContract $record): string => $record->breadcrumb->implode(' > '))
                    ->attributeData()
                    ->limitedTooltip()
                    ->limit(50)
                    ->label(__('admin::product.table.name.label')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\AttachAction::make()
                    ->recordSelect(
                        function (Forms\Components\Select $select) {
                            return $select->placeholder(__('admin::product.pages.collections.select_collection'))
                                ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search, ManageProductCollections $livewire): array {
                                    $relationModel = $livewire->getRelationship()->getRelated()::class;

                                    return get_search_builder($relationModel, $search)
                                        ->get()
                                        ->mapWithKeys(fn (CollectionContract $record): array => [$record->getKey() => $record->breadcrumb->push($record->translateAttribute('name'))->join(' > ')])
                                        ->all();
                                });
                        }
                    )->after(
                        fn () => ProductCollectionsUpdated::dispatch(
                            $this->getOwnerRecord()
                        )
                    ),
            ])
            ->actions([
                Actions\DetachAction::make()->after(
                    fn () => ProductCollectionsUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DetachBulkAction::make()->after(
                        fn () => ProductCollectionsUpdated::dispatch(
                            $this->getOwnerRecord()
                        )
                    ),
                ]),
            ]);
    }
}
