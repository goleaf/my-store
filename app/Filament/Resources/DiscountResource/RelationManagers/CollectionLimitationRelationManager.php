<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use App\Models\Collection;
use App\Support\RelationManagers\BaseRelationManager;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;

class CollectionLimitationRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'collections';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::collection.plural_label');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getDefaultTable(Table $table): Table
    {
        $prefix = config('store.database.table_prefix');

        return $table
            ->description(
                __('admin::discount.relationmanagers.collections.description')
            )
            ->modifyQueryUsing(
                fn ($query) => $query->whereIn($prefix.'collection_discount.type', ['limitation', 'exclusion'])
            )
            ->paginated(false)
            ->headerActions([
                Actions\AttachAction::make()->form(fn (Actions\AttachAction $action): array => [
                    $action->getRecordSelect(),
                    Select::make('type')
                        ->options(
                            fn () => [
                                'limitation' => __('admin::discount.relationmanagers.collections.form.type.options.limitation.label'),
                                'exclusion' => __('admin::discount.relationmanagers.collections.form.type.options.exclusion.label'),
                            ]
                        )->default('limitation'),
                ])->recordTitle(function ($record) {
                    return $record->attr('name');
                })->recordSelectSearchColumns(['attribute_data->name'])
                    ->preloadRecordSelect()
                    ->label(
                        __('admin::discount.relationmanagers.collections.actions.attach.label')
                    ),
            ])->columns([
                Tables\Columns\TextColumn::make('attribute_data.name')
                    ->label(
                        __('admin::discount.relationmanagers.collections.table.name.label')
                    )
                    ->description(fn (Collection $record): string => $record->breadcrumb->implode(' > '))
                    ->formatStateUsing(
                        fn (Model $record) => $record->attr('name')
                    ),
                Tables\Columns\TextColumn::make('pivot.type')
                    ->label(
                        __('admin::discount.relationmanagers.collections.table.type.label')
                    )->formatStateUsing(
                        fn (string $state) => __("admin::discount.relationmanagers.collections.table.type.{$state}.label")
                    ),
            ])->actions([
                Actions\DetachAction::make(),
            ])->bulkActions([
                Actions\DetachBulkAction::make(),
            ]);
    }
}
