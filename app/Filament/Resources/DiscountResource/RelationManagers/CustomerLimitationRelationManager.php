<?php

namespace App\Filament\Resources\DiscountResource\RelationManagers;

use App\Support\RelationManagers\BaseRelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use function Filament\Support\generate_search_column_expression;
use Filament\Actions;

class CustomerLimitationRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'customers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::customer.plural_label');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getDefaultTable(Table $table): Table
    {

        return $table
            ->description(
                __('admin::discount.relationmanagers.customers.description')
            )
            ->paginated(false)
            ->headerActions([
                Actions\AttachAction::make()->form(fn (Actions\AttachAction $action): array => [
                    $action->getRecordSelect(),
                ])->recordTitle(function ($record) {
                    return $record->full_name;
                })->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function ($query, $search) {
                        if (! filled($search)) {
                            return $query;
                        }

                        foreach (explode(' ', $search) as $word) {
                            $query->where(function ($query) use ($word) {
                                foreach (['first_name', 'last_name', 'company_name'] as $index => $column) {
                                    $query->{$index == 0 ? 'where' : 'orWhere'}(generate_search_column_expression($query->qualifyColumn($column), true, $query->getConnection()), 'like', "%{$word}%");
                                }
                            });
                        }
                    })
                    ->label(
                        __('admin::discount.relationmanagers.customers.actions.attach.label')
                    ),
            ])->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label(
                        __('admin::discount.relationmanagers.customers.table.name.label')
                    ),
            ])->actions([
                Actions\DetachAction::make(),
            ]);
    }
}
