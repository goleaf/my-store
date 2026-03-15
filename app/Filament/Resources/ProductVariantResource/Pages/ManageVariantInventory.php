<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductVariantResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components as SchemaComponents;

class ManageVariantInventory extends BaseEditRecord
{
    protected static string $resource = ProductVariantResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('admin::productvariant.pages.inventory.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::productvariant.pages.inventory.title');
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
            ProductVariantResource::getUrl('inventory', [
                'record' => $this->getRecord(),
            ]) => $this->getTitle(),
        ];
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-inventory');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            ProductVariantResource::getVariantSwitcherWidget(
                $this->getRecord()
            ),
        ];
    }

    public function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([
            SchemaComponents\Section::make()->schema([
                ProductVariantResource::getStockFormComponent(),
                ProductVariantResource::getBackorderFormComponent(),
                ProductVariantResource::getPurchasableFormComponent(),
                ProductVariantResource::getUnitQtyFormComponent(),
                ProductVariantResource::getQuantityIncrementFormComponent(),
                ProductVariantResource::getMinQuantityFormComponent(),
            ])->columns([
                'sm' => 1,
                'xl' => 3,
            ]),
        ]);
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
