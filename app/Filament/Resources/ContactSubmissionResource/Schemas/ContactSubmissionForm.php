<?php

namespace App\Filament\Resources\ContactSubmissionResource\Schemas;

use App\Http\Requests\Filament\Support\ContactSubmissionRequest;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContactSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        $request = static::request();

        return $schema
            ->components([
                TextInput::make('name')
                    ->rules($request->fieldRules('name'))
                    ->required($request->fieldHasRule('name', 'required')),
                TextInput::make('email')
                    ->label('Email address')
                    ->type('email')
                    ->rules($request->fieldRules('email'))
                    ->required($request->fieldHasRule('email', 'required')),
                TextInput::make('subject')
                    ->rules($request->fieldRules('subject'))
                    ->required($request->fieldHasRule('subject', 'required')),
                Textarea::make('message')
                    ->rules($request->fieldRules('message'))
                    ->required($request->fieldHasRule('message', 'required'))
                    ->columnSpanFull(),
                TextInput::make('ip_address')
                    ->rules($request->fieldRules('ip_address')),
                Toggle::make('is_read')
                    ->rules($request->fieldRules('is_read'))
                    ->required($request->fieldHasRule('is_read', 'required')),
                DateTimePicker::make('replied_at')
                    ->rules($request->fieldRules('replied_at')),
            ]);
    }

    protected static function request(): ContactSubmissionRequest
    {
        return new ContactSubmissionRequest;
    }
}
