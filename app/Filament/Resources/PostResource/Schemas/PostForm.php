<?php

namespace App\Filament\Resources\PostResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('General Information')->schema([
                    SchemaComponents\Grid::make(2)->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),
                    SchemaComponents\Grid::make(2)->schema([
                        Select::make('author_id')
                            ->relationship('author', 'name')
                            ->required()
                            ->searchable()
                            ->default(auth()->id()),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                    Textarea::make('excerpt')
                        ->rows(3)
                        ->columnSpanFull(),
                    RichEditor::make('content')
                        ->required()
                        ->columnSpanFull(),
                ]),

                SchemaComponents\Section::make('Media & Settings')->schema([
                    SchemaComponents\Grid::make(2)->schema([
                        FileUpload::make('featured_image')
                            ->image()
                            ->directory('blog/posts'),
                        SchemaComponents\Grid::make(1)->schema([
                            Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Published',
                                    'archived' => 'Archived',
                                ])
                                ->required()
                                ->default('draft'),
                            DateTimePicker::make('published_at'),
                            TextInput::make('read_time_minutes')
                                ->numeric()
                                ->suffix('mins'),
                            TagsInput::make('tags'),
                        ]),
                    ]),
                ]),

                SchemaComponents\Section::make('SEO')->schema([
                    TextInput::make('meta_title'),
                    Textarea::make('meta_description')->rows(2),
                ])->collapsed(),
            ]);
    }
}
