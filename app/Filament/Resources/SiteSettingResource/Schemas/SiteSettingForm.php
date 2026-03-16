<?php

namespace App\Filament\Resources\SiteSettingResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required(),
                Textarea::make('value')
                    ->columnSpanFull(),
                TextInput::make('group')
                    ->required()
                    ->default('general'),
                TextInput::make('type')
                    ->required()
                    ->default('text'),
                TextInput::make('label'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
