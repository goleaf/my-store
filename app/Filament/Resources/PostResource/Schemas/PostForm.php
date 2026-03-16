<?php

namespace App\Filament\Resources\PostResource\Schemas;

use App\Base\Enums\PostStatus;
use App\Http\Requests\Filament\Blog\PostRequest;
use App\Models\Post;
use App\Models\Staff;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        $request = static::request();

        return $schema
            ->components([
                Components\Section::make('General Information')->schema([
                    Components\Grid::make(2)->schema([
                        TextInput::make('title')
                            ->rules($request->fieldRules('title'))
                            ->required($request->fieldHasRule('title', 'required'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->rules(fn (?Post $record): array => static::request($record)->fieldRules('slug'))
                            ->required(fn (?Post $record): bool => static::request($record)->fieldHasRule('slug', 'required')),
                    ]),
                    Components\Grid::make(2)->schema([
                        Select::make('author_id')
                            ->relationship('author', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn (Staff $record): string => $record->full_name)
                            ->rules($request->fieldRules('author_id'))
                            ->required($request->fieldHasRule('author_id', 'required'))
                            ->searchable()
                            ->default(auth()->id()),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->rules($request->fieldRules('category_id'))
                            ->required($request->fieldHasRule('category_id', 'required'))
                            ->searchable()
                            ->preload(),
                    ]),
                    Textarea::make('excerpt')
                        ->rules($request->fieldRules('excerpt'))
                        ->rows(3)
                        ->columnSpanFull(),
                    RichEditor::make('content')
                        ->rules($request->fieldRules('content'))
                        ->required($request->fieldHasRule('content', 'required'))
                        ->columnSpanFull(),
                ]),

                Components\Section::make('Media & Settings')->schema([
                    Components\Grid::make(2)->schema([
                        FileUpload::make('featured_image')
                            ->acceptedFileTypes(['image/*'])
                            ->rules($request->fieldRules('featured_image'))
                            ->directory('blog/posts'),
                        Components\Grid::make(1)->schema([
                            Select::make('status')
                                ->options(PostStatus::options())
                                ->rules($request->fieldRules('status'))
                                ->required($request->fieldHasRule('status', 'required'))
                                ->default(PostStatus::Draft->value),
                            DateTimePicker::make('published_at')
                                ->rules($request->fieldRules('published_at')),
                            TextInput::make('read_time_minutes')
                                ->type('number')
                                ->inputMode('numeric')
                                ->step(1)
                                ->rules($request->fieldRules('read_time_minutes'))
                                ->required($request->fieldHasRule('read_time_minutes', 'required'))
                                ->suffix('mins'),
                            TagsInput::make('tags')
                                ->rules($request->fieldRules('tags')),
                        ]),
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

    protected static function request(?Post $record = null): PostRequest
    {
        return (new PostRequest)->forRecord($record);
    }
}
