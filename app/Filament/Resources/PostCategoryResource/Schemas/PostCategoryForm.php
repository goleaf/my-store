<?php

namespace App\Filament\Resources\PostCategoryResource\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('General Information')->schema([
                    SchemaComponents\Grid::make(2)->schema([
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
                ]),

                SchemaComponents\Section::make('Media & Settings')->schema([
                    SchemaComponents\Grid::make(3)->schema([
                        FileUpload::make('image')
                            ->image()
                            ->directory('blog/categories'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->default(true)
                            ->inline(false),
                    ]),
                ]),
            ]);
    }
}
