<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaxRateResource\Pages;
use App\Filament\Clusters\Taxes;
use App\Filament\Resources\TaxRateResource\RelationManagers\TaxRateAmountRelationManager;
use App\Models\Contracts\TaxRate as TaxRateContract;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class TaxRateResource extends BaseResource
{
    protected static ?string $cluster = Taxes::class;

    protected static ?string $permission = 'settings:core';

    protected static ?string $model = TaxRateContract::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('admin::taxrate.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::taxrate.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::tax');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            SchemaComponents\Section::make()->schema([
                static::getNameFormComponent(),
                static::getPriorityFormComponent(),
                static::getTaxZoneFormComponent(),
            ]),
        ];
    }

    public static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('admin::taxrate.form.name.label'))
            ->unique(column: 'name', ignoreRecord: true)
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    public static function getPriorityFormComponent(): Component
    {
        return Forms\Components\TextInput::make('priority')
            ->label(__('admin::taxrate.form.priority.label'))
            ->required()
            ->numeric()
            ->maxLength(255)
            ->autofocus();
    }

    public static function getTaxZoneFormComponent(): Component
    {
        return Forms\Components\Select::make('tax_zone_id')
            ->relationship(name: 'taxZone', titleAttribute: 'name')
            ->label(__('admin::taxrate.form.tax_zone_id.label'))
            ->live()
            ->required();
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('taxZone.name')
                ->label(__('admin::taxrate.table.tax_zone.label')),
            Tables\Columns\TextColumn::make('priority')
                ->label(__('admin::taxrate.table.priority.label')),
        ];
    }

    public static function getRelations(): array
    {
        return [
            TaxRateAmountRelationManager::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => TaxRateResource\Pages\ListTaxRates::route('/'),
            'edit' => TaxRateResource\Pages\EditTaxRate::route('/{record}/edit'),
            'create' => TaxRateResource\Pages\CreateTaxRate::route('/create'),
        ];
    }
}
