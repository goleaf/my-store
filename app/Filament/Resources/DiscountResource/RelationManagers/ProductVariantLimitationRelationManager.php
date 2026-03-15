<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use App\Store\Models\Contracts\ProductVariant as ProductVariantContract;
use App\Store\Models\Product;
use App\Store\Models\ProductVariant;
use App\Support\RelationManagers\BaseRelationManager;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;

class ProductVariantLimitationRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'discountables';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getDefaultTable(Table $table): Table
    {

        return $table
            ->heading(
                __('admin::discount.relationmanagers.productvariants.title')
            )
            ->description(
                __('admin::discount.relationmanagers.productvariants.description')
            )
            ->paginated(false)
            ->modifyQueryUsing(
                fn ($query) => $query->whereIn('type', ['limitation', 'exclusion'])
                    ->whereDiscountableType(ProductVariant::morphName())
                    ->whereHas('discountable')
            )
            ->headerActions([
                Actions\CreateAction::make()->form([
                    Forms\Components\MorphToSelect::make('discountable')
                        ->searchable(true)
                        ->types([
                            Forms\Components\MorphToSelect\Type::make(ProductVariant::modelClass())
                                ->titleAttribute('sku')
                                ->searchColumns(['sku'])
                                ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                                    $products = get_search_builder(Product::modelClass(), $search)
                                        ->get();

                                    return ProductVariant::whereIn('product_id', $products->pluck('id'))
                                        ->with(['product'])
                                        ->get()
                                        ->mapWithKeys(fn (ProductVariantContract $record): array => [$record->getKey() => $record->product->attr('name').' - '.$record->sku])
                                        ->all();
                                }),
                        ]),
                ])->label(
                    __('admin::discount.relationmanagers.productvariants.actions.attach.label')
                )->mutateFormDataUsing(function (array $data) {
                    $data['type'] = 'limitation';

                    return $data;
                }),
            ])->columns([
                Tables\Columns\TextColumn::make('discountable')
                    ->formatStateUsing(
                        fn (Model $model) => $model->discountable->getDescription()
                    )
                    ->label(
                        __('admin::discount.relationmanagers.productvariants.table.name.label')
                    ),
                Tables\Columns\TextColumn::make('discountable.sku')
                    ->label(
                        __('admin::discount.relationmanagers.productvariants.table.sku.label')
                    ),
                Tables\Columns\TextColumn::make('discountable.values')
                    ->formatStateUsing(function (Model $record) {
                        return $record->discountable->values->map(
                            fn ($value) => $value->translate('name')
                        )->join(', ');
                    })->label(
                        __('admin::discount.relationmanagers.productvariants.table.values.label')
                    ),
            ])->actions([
                Actions\DeleteAction::make(),
            ])->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }
}
