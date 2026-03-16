<?php

namespace App\Filament\Resources\StoreResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('General Information')->schema([
                    SchemaComponents\Grid::make(3)->schema([
                        Select::make('owner_id')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),
                    Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                    SchemaComponents\Grid::make(2)->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->directory('stores/logos'),
                        FileUpload::make('banner')
                            ->image()
                            ->directory('stores/banners'),
                    ]),
                ]),
                Section::make('Contact & Address')->schema([
                    SchemaComponents\Grid::make(2)->schema([
                        TextInput::make('email')->email(),
                        TextInput::make('phone')->tel(),
                    ]),
                    TextInput::make('address_line_1'),
                    SchemaComponents\Grid::make(3)->schema([
                        TextInput::make('city'),
                        TextInput::make('state'),
                        TextInput::make('country'),
                    ]),
                ]),
                SchemaComponents\Section::make('Settings & Stats')->schema([
                    SchemaComponents\Grid::make(4)->schema([
                        TextInput::make('commission_rate')->numeric()->default(0)->suffix('%'),
                        TextInput::make('rating_avg')->numeric()->disabled(),
                        TextInput::make('total_reviews')->numeric()->disabled(),
                        Toggle::make('is_active')->default(true)->inline(false),
                        Toggle::make('is_verified')->default(false)->inline(false),
                    ]),
                ]),
                SchemaComponents\Section::make('SEO')->schema([
                    TextInput::make('meta_title'),
                    Textarea::make('meta_description')->rows(2),
                ])->collapsed(),
            ]);
    }
}
