<?php

namespace App\Filament\Resources;

use App\Admin\Filament\Resources\TaxClassResource\Pages;
use App\Filament\Clusters\Taxes;
use App\Store\Models\Contracts\TaxClass as TaxClassContract;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class TaxClassResource extends BaseResource
{
    protected static ?string $cluster = Taxes::class;

    protected static ?string $permission = 'settings:core';

    protected static ?string $model = TaxClassContract::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('admin::taxclass.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::taxclass.plural_label');
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
                static::getDefaultFormComponent(),
            ]),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('admin::taxclass.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getDefaultFormComponent(): Component
    {
        return Forms\Components\Toggle::make('default')
            ->label(__('admin::taxzone.form.default.label'));
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
            Tables\Columns\TextColumn::make('name')
                ->label(__('admin::taxclass.table.name.label'))
                ->formatStateUsing(fn ($state, Model $record) => $state.($record->default ? ' • '.__('admin::taxclass.table.default.label') : '')),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => TaxClassResource\Pages\ListTaxClasses::route('/'),
            'create' => TaxClassResource\Pages\CreateTaxClass::route('/create'),
            'edit' => TaxClassResource\Pages\EditTaxClass::route('/{record}/edit'),
        ];
    }
}
