<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Events\ProductCustomerGroupsUpdated;
use App\Support\RelationManagers\BaseRelationManager;
use Filament;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Filament\Actions;
use Filament\Schemas\Components;

class CustomerGroupRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'customerGroups';

    public ?string $description = null;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin::relationmanagers.customer_groups.title');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    protected function getPivotColumns(): array
    {
        return collect($this->getRelationship()->getPivotColumns())
            ->reject(
                fn ($column) => in_array($column, ['created_at', 'updated_at', 'deleted_at', 'ends_at', 'starts_at'])
            )->toArray();
    }

    public function getDefaultForm(Schema $schema): Schema
    {
        return $schema->components(
            static::getFormInputs(
                $this->getPivotColumns()
            )
        );
    }

    protected static function getFormInputs(array $pivotColumns = []): array
    {
        $columns = collect($pivotColumns)->map(function ($column) {
            return Filament\Forms\Components\Toggle::make($column)->label(
                __("admin::relationmanagers.customer_groups.form.{$column}.label")
            );
        });

        $grid = [];

        if (! $columns->isEmpty()) {
            $grid[] = Components\Grid::make($columns->count())->schema(
                $columns->toArray()
            );
        }

        return [
            ...$grid,
            ...[Components\Grid::make(2)->schema([
                Filament\Forms\Components\DateTimePicker::make('starts_at')->label(
                    __('admin::relationmanagers.customer_groups.form.starts_at.label')
                ),
                Filament\Forms\Components\DateTimePicker::make('ends_at')->label(
                    __('admin::relationmanagers.customer_groups.form.ends_at.label')
                ),
            ])],
        ];
    }

    public function getDefaultTable(Table $table): Table
    {
        $pivotColumns = collect($this->getPivotColumns())->map(function ($column) {
            return Tables\Columns\IconColumn::make($column)->label(
                __("admin::relationmanagers.customer_groups.table.{$column}.label")
            )
                ->color(fn ($state): string => $state ? 'success' : 'warning')
                ->icon(fn ($state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle');
        })->toArray();

        return $table
            ->description(
                $this->description ?: __('admin::relationmanagers.customer_groups.table.description', [
                    'type' => Str::lower(class_basename(get_class($this->getOwnerRecord()))),
                ])
            )
            ->paginated(false)
            ->headerActions([
                Actions\AttachAction::make()->form(fn (Actions\AttachAction $action): array => [
                    $action->getRecordSelect(),
                    ...static::getFormInputs(),
                ])->recordTitle(function ($record) {
                    return $record->name;
                })->preloadRecordSelect()
                    ->label(
                        __('admin::relationmanagers.customer_groups.actions.attach.label')
                    )->after(
                        fn () => ProductCustomerGroupsUpdated::dispatch($this->getOwnerRecord())
                    ),
            ])->columns([
                ...[
                    Tables\Columns\TextColumn::make('name')->label(
                        __('admin::relationmanagers.customer_groups.table.name.label')
                    ),
                ],
                ...$pivotColumns,
                ...[
                    Tables\Columns\TextColumn::make('starts_at')->label(
                        __('admin::relationmanagers.customer_groups.table.starts_at.label')
                    )->dateTime(),
                    Tables\Columns\TextColumn::make('ends_at')->label(
                        __('admin::relationmanagers.customer_groups.table.ends_at.label')
                    )->dateTime(),
                ],
            ])->actions([
                Actions\EditAction::make()->after(
                    fn () => ProductCustomerGroupsUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
            ]);
    }
}
