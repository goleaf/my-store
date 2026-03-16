<?php

namespace App\Filament\Resources\PromoBlockResource\Schemas;

use App\Http\Requests\Filament\Marketing\PromoBlockRequest;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromoBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        $request = static::request();

        return $schema
            ->components([
                TextInput::make('title')
                    ->rules($request->fieldRules('title'))
                    ->required($request->fieldHasRule('title', 'required')),
                TextInput::make('subtitle')
                    ->rules($request->fieldRules('subtitle')),
                TextInput::make('badge_text')
                    ->rules($request->fieldRules('badge_text')),
                FileUpload::make('image')
                    ->acceptedFileTypes(['image/*'])
                    ->rules($request->fieldRules('image')),
                TextInput::make('bg_color')
                    ->rules($request->fieldRules('bg_color')),
                TextInput::make('position')
                    ->rules($request->fieldRules('position'))
                    ->required($request->fieldHasRule('position', 'required'))
                    ->default('middle'),
                TextInput::make('cta_text')
                    ->rules($request->fieldRules('cta_text')),
                TextInput::make('cta_url')
                    ->type('url')
                    ->rules($request->fieldRules('cta_url')),
                TextInput::make('sort_order')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('sort_order'))
                    ->required($request->fieldHasRule('sort_order', 'required'))
                    ->default(0),
                Toggle::make('is_active')
                    ->rules($request->fieldRules('is_active'))
                    ->required($request->fieldHasRule('is_active', 'required')),
            ]);
    }

    protected static function request(): PromoBlockRequest
    {
        return new PromoBlockRequest;
    }
}
