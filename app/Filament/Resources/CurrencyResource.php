<?php

namespace App\Filament\Resources;

use App\Admin\Filament\Resources\CurrencyResource\Pages;
use App\Store\Models\Contracts\Currency as CurrencyContract;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components as SchemaComponents;

class CurrencyResource extends BaseResource
{
    protected static ?string $permission = 'settings:core';

    protected static ?string $model = CurrencyContract::class;

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('admin::currency.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::currency.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::currencies');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.settings');
    }

    public static function getDefaultForm(Schema $schema): Schema
    {
        return $schema->components([
            SchemaComponents\Section::make('details')->schema(
                static::getMainFormComponents()
            )->heading()->columns(),
        ]);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
            static::getCodeFormComponent(),
            static::getExchangeRateFormComponent(),
            static::getDecimalPlacesFormComponent(),
            static::getEnabledFormComponent(),
            static::getDefaultFormComponent(),
            static::getSyncPricesFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('admin::currency.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getCodeFormComponent(): Component
    {
        return Forms\Components\TextInput::make('code')
            ->label(__('admin::currency.form.code.label'))
            ->required()
            ->unique(ignoreRecord: true)
            ->minLength(3)
            ->maxLength(3);
    }

    protected static function getExchangeRateFormComponent(): Component
    {
        return Forms\Components\TextInput::make('exchange_rate')
            ->label(__('admin::currency.form.exchange_rate.label'))
            ->numeric()
            ->required();
    }

    protected static function getDecimalPlacesFormComponent(): Component
    {
        return Forms\Components\TextInput::make('decimal_places')
            ->label(__('admin::currency.form.decimal_places.label'))
            ->numeric()
            ->required();
    }

    protected static function getEnabledFormComponent(): Component
    {
        return Forms\Components\Toggle::make('enabled')
            ->label(__('admin::currency.form.enabled.label'));
    }

    protected static function getDefaultFormComponent(): Component
    {
        return Forms\Components\Toggle::make('default')
            ->label(__('admin::currency.form.default.label'));
    }

    protected static function getSyncPricesFormComponent(): Component
    {
        return Forms\Components\Toggle::make('sync_prices')
            ->label(__('admin::currency.form.sync_prices.label'))
            ->helperText(__('admin::currency.form.sync_prices.helper_text'))
            ->hidden(
                fn (?Model $record) => (bool) $record?->default
            )
            ->default(true);
    }

    protected static function getDefaultTable(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('admin::currency.table.name.label'))
                ->formatStateUsing(fn ($state, Model $record) => $state.($record->default ? ' • '.__('admin::currency.table.default.label') : '')),
            Tables\Columns\TextColumn::make('code')
                ->label(__('admin::currency.table.code.label')),
            Tables\Columns\TextColumn::make('exchange_rate')
                ->label(__('admin::currency.table.exchange_rate.label')),
            Tables\Columns\TextColumn::make('decimal_places')
                ->label(__('admin::currency.table.decimal_places.label')),
            Tables\Columns\IconColumn::make('enabled')
                ->boolean()
                ->label(__('admin::currency.table.enabled.label')),
            Tables\Columns\IconColumn::make('sync_prices')
                ->boolean()
                ->label(__('admin::currency.table.sync_prices.label')),
        ]);
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => CurrencyResource\Pages\ListCurrencies::route('/'),
            'create' => CurrencyResource\Pages\CreateCurrency::route('/create'),
            'edit' => CurrencyResource\Pages\EditCurrency::route('/{record}/edit'),
        ];
    }
}
