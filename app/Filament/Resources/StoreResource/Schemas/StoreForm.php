<?php

namespace App\Filament\Resources\StoreResource\Schemas;

use App\Http\Requests\Filament\Store\StoreRequest;
use App\Models\Customer;
use App\Models\Store;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        $request = static::request();

        return $schema
            ->components([
                Components\Section::make('General Information')->schema([
                    Components\Grid::make(3)->schema([
                        Select::make('owner_id')
                            ->relationship('owner', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn (Customer $record): string => $record->name)
                            ->searchable()
                            ->preload()
                            ->rules($request->fieldRules('owner_id'))
                            ->required($request->fieldHasRule('owner_id', 'required')),
                        TextInput::make('name')
                            ->rules($request->fieldRules('name'))
                            ->required($request->fieldHasRule('name', 'required'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->rules(fn (?Store $record): array => static::request($record)->fieldRules('slug'))
                            ->required(fn (?Store $record): bool => static::request($record)->fieldHasRule('slug', 'required')),
                    ]),
                    Textarea::make('description')
                        ->rules($request->fieldRules('description'))
                        ->rows(3)
                        ->columnSpanFull(),
                    Components\Grid::make(2)->schema([
                        FileUpload::make('logo')
                            ->acceptedFileTypes(['image/*'])
                            ->rules($request->fieldRules('logo'))
                            ->directory('stores/logos'),
                        FileUpload::make('banner')
                            ->acceptedFileTypes(['image/*'])
                            ->rules($request->fieldRules('banner'))
                            ->directory('stores/banners'),
                    ]),
                ]),
                Components\Section::make('Contact & Address')->schema([
                    Components\Grid::make(2)->schema([
                        TextInput::make('email')
                            ->type('email')
                            ->rules($request->fieldRules('email')),
                        TextInput::make('phone')
                            ->type('tel')
                            ->rules($request->fieldRules('phone')),
                    ]),
                    TextInput::make('address_line_1')
                        ->rules($request->fieldRules('address_line_1')),
                    Components\Grid::make(3)->schema([
                        TextInput::make('city')
                            ->rules($request->fieldRules('city')),
                        TextInput::make('state')
                            ->rules($request->fieldRules('state')),
                        TextInput::make('country')
                            ->rules($request->fieldRules('country')),
                    ]),
                ]),
                Components\Section::make('Settings & Stats')->schema([
                    Components\Grid::make(4)->schema([
                        TextInput::make('commission_rate')
                            ->type('number')
                            ->inputMode('decimal')
                            ->step('0.01')
                            ->rules($request->fieldRules('commission_rate'))
                            ->required($request->fieldHasRule('commission_rate', 'required'))
                            ->default(0)
                            ->suffix('%'),
                        TextInput::make('rating_avg')
                            ->type('number')
                            ->inputMode('decimal')
                            ->step('0.01')
                            ->rules($request->fieldRules('rating_avg'))
                            ->required($request->fieldHasRule('rating_avg', 'required'))
                            ->disabled(),
                        TextInput::make('total_reviews')
                            ->type('number')
                            ->inputMode('numeric')
                            ->step(1)
                            ->rules($request->fieldRules('total_reviews'))
                            ->required($request->fieldHasRule('total_reviews', 'required'))
                            ->disabled(),
                        Toggle::make('is_active')
                            ->rules($request->fieldRules('is_active'))
                            ->required($request->fieldHasRule('is_active', 'required'))
                            ->default(true)
                            ->inline(false),
                        Toggle::make('is_verified')
                            ->rules($request->fieldRules('is_verified'))
                            ->required($request->fieldHasRule('is_verified', 'required'))
                            ->default(false)
                            ->inline(false),
                    ]),
                ]),
                Components\Section::make('SEO')->schema([
                    TextInput::make('meta_title')
                        ->rules($request->fieldRules('meta_title')),
                    Textarea::make('meta_description')
                        ->rules($request->fieldRules('meta_description'))
                        ->rows(2),
                ])->collapsed(),
            ]);
    }

    protected static function request(?Store $record = null): StoreRequest
    {
        return (new StoreRequest)->forRecord($record);
    }
}
