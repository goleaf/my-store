<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductVariantResource\Pages\ManageVariantShipping;
use App\Store\Models\Contracts\ProductVariant as ProductVariantContract;
use App\Support\Forms\Components\TextInputSelectAffix;
use App\Support\Pages\BaseEditRecord;
use Cartalyst\Converter\Laravel\Facades\Converter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components as SchemaComponents;

class ManageProductShipping extends BaseEditRecord
{
    protected static string $resource = ProductResource::class;

    public bool $shippable = true;

    public ?array $dimensions = [
        'length_value' => 0,
        'length_unit' => 'mm',
        'width_value' => 0,
        'width_unit' => 'mm',
        'height_value' => 0,
        'height_unit' => 'mm',
        'weight_value' => 0,
        'weight_unit' => 'kg',
    ];

    public function getTitle(): string|Htmlable
    {
        return __('admin::product.pages.shipping.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::product.pages.shipping.label');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return $parameters['record']->variants()->withTrashed()->count() == 1;
    }

    public function getBreadcrumb(): string
    {
        return __('admin::product.pages.shipping.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-shipping');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $variant = $this->getVariant();

        $this->dimensions = [
            ...$this->dimensions,
            ...$variant->only(array_keys($this->dimensions)),
        ];
        $this->shippable = $variant->shippable;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variant = $this->getVariant();

        $variant->update([
            ...[
                'shippable' => $this->shippable,
                'volume_unit' => 'l',
                'volume_value' => ManageVariantShipping::getVolume(
                    [
                        'value' => $this->dimensions['width_value'],
                        'unit' => $this->dimensions['width_unit'],
                    ],
                    [
                        'value' => $this->dimensions['length_value'],
                        'unit' => $this->dimensions['length_unit'],
                    ],
                    [
                        'value' => $this->dimensions['height_value'],
                        'unit' => $this->dimensions['height_unit'],
                    ]
                ),
            ],
            ...$this->dimensions,
        ]);

        return $record;
    }

    protected function getVariant(): ProductVariantContract
    {
        return $this->getRecord()->variants()->withTrashed()->first();
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        $measurements = Converter::getMeasurements();

        $lengths = collect(
            array_keys($measurements['length'] ?? [])
        )->mapWithKeys(
            fn ($value) => [$value => $value]
        );

        $weights = collect(
            array_keys($measurements['weight'] ?? [])
        )->mapWithKeys(
            fn ($value) => [$value => $value]
        );

        return $schema->components([
            SchemaComponents\Section::make()->schema([
                Toggle::make('shippable')->label(
                    __('admin::productvariant.form.shippable.label')
                )->columnSpan(2),

                TextInputSelectAffix::make('dimensions.length_value')
                    ->label(
                        __('admin::productvariant.form.length_value.label')
                    )
                    ->numeric()
                    ->select(
                        fn () => Select::make('length_unit')
                            ->options($lengths)
                            ->label(
                                __('admin::pproductvariant.form.length_unit.label')
                            )->selectablePlaceholder(false)
                    ),
                TextInputSelectAffix::make('dimensions.width_value')
                    ->label(
                        __('admin::productvariant.form.width_value.label')
                    )
                    ->numeric()
                    ->select(
                        fn () => Select::make('width_unit')
                            ->options($lengths)
                            ->label(
                                __('admin::productvariant.form.width_unit.label')
                            )->selectablePlaceholder(false)
                    ),
                TextInputSelectAffix::make('dimensions.height_value')
                    ->label(
                        __('admin::productvariant.form.height_value.label')
                    )
                    ->numeric()
                    ->select(
                        fn () => Select::make('height_unit')
                            ->options($lengths)
                            ->label(
                                __('admin::productvariant.form.height_unit.label')
                            )->selectablePlaceholder(false)
                    ),
                TextInputSelectAffix::make('dimensions.weight_value')
                    ->label(
                        __('admin::productvariant.form.weight_value.label')
                    )
                    ->numeric()
                    ->select(
                        fn () => Select::make('weight_unit')
                            ->options($weights)
                            ->label(
                                __('admin::productvariant.form.weight_unit.label')
                            )->selectablePlaceholder(false)
                    ),
            ])->columns([
                'sm' => 1,
                'xl' => 2,
            ]),
        ])->statePath('');
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
