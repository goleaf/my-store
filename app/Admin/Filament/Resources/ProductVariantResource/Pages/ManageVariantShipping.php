<?php

namespace App\Admin\Filament\Resources\ProductVariantResource\Pages;

use Cartalyst\Converter\Laravel\Facades\Converter;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use App\Admin\Filament\Resources\ProductResource;
use App\Admin\Filament\Resources\ProductVariantResource;
use App\Admin\Support\Pages\BaseEditRecord;

class ManageVariantShipping extends BaseEditRecord
{
    protected static string $resource = ProductVariantResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('admin::productvariant.pages.shipping.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::productvariant.pages.shipping.title');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-shipping');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            ProductVariantResource::getVariantSwitcherWidget(
                $this->getRecord()
            ),
        ];
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()->url(function (Model $record) {
            return ProductResource::getUrl('variants', [
                'record' => $record->product,
            ]);
        });
    }

    public function getBreadcrumbs(): array
    {
        return [
            ...ProductVariantResource::getBaseBreadcrumbs(
                $this->getRecord()
            ),
            ProductVariantResource::getUrl('shipping', [
                'record' => $this->getRecord(),
            ]) => $this->getTitle(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $volume = static::getVolume(
            [
                'value' => $data['width_value'],
                'unit' => $data['width_unit'] ?? $record->width_unit,
            ],
            [
                'value' => $data['length_value'],
                'unit' => $data['length_unit'] ?? $record->length_unit,
            ],
            [
                'value' => $data['height_value'],
                'unit' => $data['height_unit'] ?? $record->height_unit,
            ]
        );

        $record->update([
            ...$data,
            ...[
                'volume_unit' => 'l',
                'volume_value' => $volume,
            ],
        ]);

        return $record;
    }

    public static function getVolume($width = [], $length = [], $height = [])
    {
        $width = Converter::value($width['value'])
            ->from('length.'.$width['unit'])
            ->to('length.cm')
            ->convert()
            ->getValue();
        $length = Converter::value($length['value'])
            ->from('length.'.$length['unit'])
            ->to('length.cm')
            ->convert()
            ->getValue();

        $height = Converter::value($height['value'])
            ->from('length.'.$height['unit'])
            ->to('length.cm')
            ->convert()
            ->getValue();

        return Converter::from('volume.ml')->to('volume.l')->value($length * $width * $height)->convert()->getValue();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([
            Section::make()->schema([
                ProductVariantResource::getShippableFormComponent(),
                ProductVariantResource::getLengthFormComponent(),
                ProductVariantResource::getWidthFormComponent(),
                ProductVariantResource::getHeightFormComponent(),
                ProductVariantResource::getWeightFormComponent(),
            ])->columns([
                'sm' => 1,
                'xl' => 2,
            ]),
        ]);
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
