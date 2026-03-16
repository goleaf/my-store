<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use App\Models\Product;
use App\Support\RelationManagers\BaseRelationManager;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use App\Models\Contracts;

class ProductLimitationRelationManager extends BaseRelationManager
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
                __('admin::discount.relationmanagers.products.title')
            )
            ->description(
                __('admin::discount.relationmanagers.products.description')
            )
            ->paginated(false)
            ->modifyQueryUsing(
                fn ($query) => $query->whereIn('type', ['limitation', 'exclusion'])
                    ->whereDiscountableType(Product::morphName())
                    ->whereHas('discountable')
                    ->with('discountable.thumbnail')
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
                        ]),
                ])->label(
                    __('admin::discount.relationmanagers.products.actions.attach.label')
                )->mutateFormDataUsing(function (array $data) {
                    $data['type'] = 'limitation';

                    return $data;
                }),
            ])->columns([
                Tables\Columns\ImageColumn::make('discountable_thumbnail')
                    ->state(fn (Model $record): string => $record->discountable?->getThumbnailImage() ?? '')
                    ->square()
                    ->label(''),
                Tables\Columns\TextColumn::make('discountable.attribute_data.name')
                    ->label(
                        __('admin::discount.relationmanagers.products.table.name.label')
                    )
                    ->formatStateUsing(
                        fn (Model $record) => $record->discountable->attr('name')
                    ),
            ])->actions([
                Actions\DeleteAction::make(),
            ])->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }
}
