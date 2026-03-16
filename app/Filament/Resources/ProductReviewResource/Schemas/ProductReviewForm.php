<?php

namespace App\Filament\Resources\ProductReviewResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('rating')
                    ->required()
                    ->numeric(),
                TextInput::make('rating_flavor')
                    ->numeric(),
                TextInput::make('rating_value')
                    ->numeric(),
                TextInput::make('rating_scent')
                    ->numeric(),
                TextInput::make('title'),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('images')
                    ->columnSpanFull(),
                TextInput::make('helpful_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_verified_purchase')
                    ->required(),
                Toggle::make('is_approved')
                    ->required(),
                Toggle::make('is_flagged')
                    ->required(),
                Textarea::make('admin_reply')
                    ->columnSpanFull(),
                DateTimePicker::make('admin_replied_at'),
            ]);
    }
}
