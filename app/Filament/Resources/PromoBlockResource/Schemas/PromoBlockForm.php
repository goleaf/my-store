<?php

namespace App\Filament\Resources\PromoBlockResource\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromoBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('subtitle'),
                TextInput::make('badge_text'),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('bg_color'),
                TextInput::make('position')
                    ->required()
                    ->default('middle'),
                TextInput::make('cta_text'),
                TextInput::make('cta_url')
                    ->url(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
