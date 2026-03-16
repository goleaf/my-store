<?php

namespace App\Filament\Resources\ProductReviewResource\Schemas;

use App\Http\Requests\Filament\Catalog\ProductReviewRequest;
use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        $request = static::request();

        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Product $record): string => $record->translateAttribute('name'))
                    ->rules($request->fieldRules('product_id'))
                    ->required($request->fieldHasRule('product_id', 'required'))
                    ->searchable()
                    ->preload(),
                Select::make('customer_id')
                    ->relationship('customer', 'email')
                    ->rules($request->fieldRules('customer_id'))
                    ->required($request->fieldHasRule('customer_id', 'required'))
                    ->searchable()
                    ->preload(),
                TextInput::make('rating')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('rating'))
                    ->required($request->fieldHasRule('rating', 'required')),
                TextInput::make('rating_flavor')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('rating_flavor'))
                    ->required($request->fieldHasRule('rating_flavor', 'required')),
                TextInput::make('rating_value')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('rating_value'))
                    ->required($request->fieldHasRule('rating_value', 'required')),
                TextInput::make('rating_scent')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('rating_scent'))
                    ->required($request->fieldHasRule('rating_scent', 'required')),
                TextInput::make('title')
                    ->rules($request->fieldRules('title')),
                Textarea::make('body')
                    ->rules($request->fieldRules('body'))
                    ->required($request->fieldHasRule('body', 'required'))
                    ->columnSpanFull(),
                Textarea::make('images')
                    ->rules($request->fieldRules('images'))
                    ->columnSpanFull(),
                TextInput::make('helpful_count')
                    ->type('number')
                    ->inputMode('numeric')
                    ->step(1)
                    ->rules($request->fieldRules('helpful_count'))
                    ->required($request->fieldHasRule('helpful_count', 'required'))
                    ->default(0),
                Toggle::make('is_verified_purchase')
                    ->rules($request->fieldRules('is_verified_purchase'))
                    ->required($request->fieldHasRule('is_verified_purchase', 'required')),
                Toggle::make('is_approved')
                    ->rules($request->fieldRules('is_approved'))
                    ->required($request->fieldHasRule('is_approved', 'required')),
                Toggle::make('is_flagged')
                    ->rules($request->fieldRules('is_flagged'))
                    ->required($request->fieldHasRule('is_flagged', 'required')),
                Textarea::make('admin_reply')
                    ->rules($request->fieldRules('admin_reply'))
                    ->columnSpanFull(),
                DateTimePicker::make('admin_replied_at')
                    ->rules($request->fieldRules('admin_replied_at')),
            ]);
    }

    protected static function request(): ProductReviewRequest
    {
        return new ProductReviewRequest;
    }
}
