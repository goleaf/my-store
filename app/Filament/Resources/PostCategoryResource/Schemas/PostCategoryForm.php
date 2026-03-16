<?php

namespace App\Filament\Resources\PostCategoryResource\Schemas;

use App\Http\Requests\Filament\Blog\PostCategoryRequest;
use App\Models\PostCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        $request = static::request();

        return $schema
            ->components([
                Components\Section::make('General Information')->schema([
                    Components\Grid::make(2)->schema([
                        TextInput::make('name')
                            ->rules($request->fieldRules('name'))
                            ->required($request->fieldHasRule('name', 'required'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->rules(fn (?PostCategory $record): array => static::request($record)->fieldRules('slug'))
                            ->required(fn (?PostCategory $record): bool => static::request($record)->fieldHasRule('slug', 'required')),
                    ]),
                    Textarea::make('description')
                        ->rules($request->fieldRules('description'))
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

                Components\Section::make('Media & Settings')->schema([
                    Components\Grid::make(3)->schema([
                        FileUpload::make('image')
                            ->acceptedFileTypes(['image/*'])
                            ->rules($request->fieldRules('image'))
                            ->directory('blog/categories'),
                        TextInput::make('sort_order')
                            ->type('number')
                            ->inputMode('numeric')
                            ->step(1)
                            ->rules($request->fieldRules('sort_order'))
                            ->required($request->fieldHasRule('sort_order', 'required'))
                            ->default(0),
                        Toggle::make('is_active')
                            ->rules($request->fieldRules('is_active'))
                            ->required($request->fieldHasRule('is_active', 'required'))
                            ->default(true)
                            ->inline(false),
                    ]),
                ]),
            ]);
    }

    protected static function request(?PostCategory $record = null): PostCategoryRequest
    {
        return (new PostCategoryRequest)->forRecord($record);
    }
}
