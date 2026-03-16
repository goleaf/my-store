<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurrencyResource\Pages;
use App\Http\Requests\Filament\System\CurrencyRequest;
use App\Models\Contracts\Currency;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Schemas\Components;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class CurrencyResource extends BaseResource
{
    protected static ?string $permission = 'settings:core';

    protected static ?string $model = Currency::class;

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
            Components\Section::make('details')->schema(
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
        $request = static::request();

        return Forms\Components\TextInput::make('name')
            ->label(__('admin::currency.form.name.label'))
            ->rules($request->fieldRules('name'))
            ->required($request->fieldHasRule('name', 'required'))
            ->autofocus();
    }

    protected static function getCodeFormComponent(): Component
    {
        return Forms\Components\TextInput::make('code')
            ->label(__('admin::currency.form.code.label'))
            ->rules(fn (?Model $record): array => static::request($record)->fieldRules('code'))
            ->required(fn (?Model $record): bool => static::request($record)->fieldHasRule('code', 'required'))
            ->extraInputAttributes([
                'maxlength' => 3,
            ]);
    }

    protected static function getExchangeRateFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\TextInput::make('exchange_rate')
            ->label(__('admin::currency.form.exchange_rate.label'))
            ->type('number')
            ->inputMode('decimal')
            ->step('0.0001')
            ->rules($request->fieldRules('exchange_rate'))
            ->required($request->fieldHasRule('exchange_rate', 'required'));
    }

    protected static function getDecimalPlacesFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\TextInput::make('decimal_places')
            ->label(__('admin::currency.form.decimal_places.label'))
            ->type('number')
            ->inputMode('numeric')
            ->step(1)
            ->rules($request->fieldRules('decimal_places'))
            ->required($request->fieldHasRule('decimal_places', 'required'));
    }

    protected static function getEnabledFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\Toggle::make('enabled')
            ->label(__('admin::currency.form.enabled.label'))
            ->rules($request->fieldRules('enabled'))
            ->required($request->fieldHasRule('enabled', 'required'));
    }

    protected static function getDefaultFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\Toggle::make('default')
            ->label(__('admin::currency.form.default.label'))
            ->rules($request->fieldRules('default'))
            ->required($request->fieldHasRule('default', 'required'));
    }

    protected static function getSyncPricesFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\Toggle::make('sync_prices')
            ->label(__('admin::currency.form.sync_prices.label'))
            ->rules($request->fieldRules('sync_prices'))
            ->required($request->fieldHasRule('sync_prices', 'required'))
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
                ->formatStateUsing(fn ($state, Model $record) => $state . ($record->default ? ' • ' . __('admin::currency.table.default.label') : '')),
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

    protected static function request(?Model $record = null): CurrencyRequest
    {
        return (new CurrencyRequest)->forRecord($record);
    }
}
