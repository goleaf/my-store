<?php

namespace App\Filament\Resources;

use App\Admin\Filament\Resources\CustomerGroupResource\Pages;
use App\Store\Models\Contracts\CustomerGroup as CustomerGroupContract;
use App\Support\Forms\Components\Attributes;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CustomerGroupResource extends BaseResource
{
    protected static ?string $permission = 'settings:core';

    protected static ?string $model = CustomerGroupContract::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('admin::customergroup.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::customergroup.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::customer-groups');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.settings');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            static::getHandleFormComponent(),
            static::getDefaultFormComponent(),
            static::getAttributeDataFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('admin::customergroup.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getHandleFormComponent(): Component
    {
        return Forms\Components\TextInput::make('handle')
            ->label(__('admin::customergroup.form.handle.label'))
            ->required()
            ->unique(ignoreRecord: true)
            ->minLength(3)
            ->maxLength(255);
    }

    protected static function getDefaultFormComponent(): Component
    {
        return Forms\Components\Toggle::make('default')
            ->label(__('admin::customergroup.form.default.label'));
    }

    protected static function getAttributeDataFormComponent(): Component
    {
        return Attributes::make();
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('admin::customergroup.table.name.label'))
                ->formatStateUsing(fn ($state, Model $record) => $state.($record->default ? ' • '.__('admin::customergroup.table.default.label') : '')),
            Tables\Columns\TextColumn::make('handle')
                ->label(__('admin::customergroup.table.handle.label')),
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
            'index' => CustomerGroupResource\Pages\ListCustomerGroups::route('/'),
            'create' => CustomerGroupResource\Pages\CreateCustomerGroup::route('/create'),
            'edit' => CustomerGroupResource\Pages\EditCustomerGroup::route('/{record}/edit'),
        ];
    }
}
