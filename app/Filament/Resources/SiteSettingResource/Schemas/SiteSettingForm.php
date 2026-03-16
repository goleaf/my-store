<?php

namespace App\Filament\Resources\SiteSettingResource\Schemas;

use App\Http\Requests\Filament\System\SiteSettingRequest;
use App\Models\SiteSetting;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        $request = static::request();

        return $schema
            ->components([
                TextInput::make('key')
                    ->rules(fn (?SiteSetting $record): array => static::request($record)->fieldRules('key'))
                    ->required(fn (?SiteSetting $record): bool => static::request($record)->fieldHasRule('key', 'required')),
                Textarea::make('value')
                    ->rules($request->fieldRules('value'))
                    ->columnSpanFull(),
                TextInput::make('group')
                    ->rules($request->fieldRules('group'))
                    ->required($request->fieldHasRule('group', 'required'))
                    ->default('general'),
                TextInput::make('type')
                    ->rules($request->fieldRules('type'))
                    ->required($request->fieldHasRule('type', 'required'))
                    ->default('text'),
                TextInput::make('label')
                    ->rules($request->fieldRules('label')),
                Textarea::make('description')
                    ->rules($request->fieldRules('description'))
                    ->columnSpanFull(),
            ]);
    }

    protected static function request(?SiteSetting $record = null): SiteSettingRequest
    {
        return (new SiteSettingRequest)->forRecord($record);
    }
}
