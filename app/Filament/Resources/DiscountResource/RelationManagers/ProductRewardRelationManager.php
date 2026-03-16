<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\RelationManagers\BaseRelationManager;
use App\Support\Tables\Columns\ThumbnailImageColumn;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use App\Models\Contracts;

class ProductRewardRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'discountables';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::discount.relationmanagers.rewards.title');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getDefaultTable(Table $table): Table
    {

        return $table
            ->heading(
                __('admin::discount.relationmanagers.rewards.title')
            )
            ->description(
                __('admin::discount.relationmanagers.rewards.description')
            )
            ->paginated(false)
            ->modifyQueryUsing(
                fn ($query) => $query->whereIn('type', ['reward'])
                    ->whereIn('discountable_type', [Product::morphName(), ProductVariant::morphName()])
                    ->whereHas('discountable')
            )
            ->headerActions([
                Actions\CreateAction::make()->form([
                    Forms\Components\MorphToSelect::make('discountable')
                        ->searchable(true)
                        ->types([
                            Forms\Components\MorphToSelect\Type::make(Product::modelClass())
                                ->titleAttribute('name.en')
                                ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                                    return get_search_builder(Product::modelClass(), $search)
                                        ->get()
                                        ->mapWithKeys(fn (Contracts\Product $record): array => [$record->getKey() => $record->attr('name')])
                                        ->all();
                                }),

                            Forms\Components\MorphToSelect\Type::make(ProductVariant::modelClass())
                                ->titleAttribute('sku')
                                ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                                    return get_search_builder(ProductVariant::modelClass(), $search)
                                        ->orWhere('sku', 'like', $search.'%')
                                        ->get()
                                        ->mapWithKeys(fn (Contracts\ProductVariant $record): array => [$record->getKey() => $record->product->attr('name').' - '.$record->sku])
                                        ->all();
                                }),
                        ]),
                ])->label(
                    __('admin::discount.relationmanagers.rewards.actions.attach.label')
                )->mutateFormDataUsing(function (array $data) {
                    $data['type'] = 'reward';

                    return $data;
                }),
            ])->columns([
                ThumbnailImageColumn::make('discountable_id')
                    ->resolveThumbnailUrlUsing(fn (?Model $record) => $record?->discountable?->getThumbnailImage())
                    ->label(''),

                Tables\Columns\TextColumn::make('discountable.id')
                    ->label(
                        __('admin::discount.relationmanagers.conditions.table.name.label')
                    )
                    ->formatStateUsing(
                        fn (Model $record) => $record->discountable instanceof Contracts\ProductVariant ? $record->discountable->product->attr('name').' - '.$record->discountable->sku : $record->discountable->attr('name')
                    ),

                Tables\Columns\TextColumn::make('discountable_type')
                    ->label(
                        __('admin::discount.relationmanagers.conditions.table.type.label')
                    )
                    ->formatStateUsing(
                        fn (Model $record) => str($record->discountable->morphName())->replace('_', ' ')->title(),
                    ),

            ])->actions([
                Actions\DeleteAction::make(),
            ])->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }
}
