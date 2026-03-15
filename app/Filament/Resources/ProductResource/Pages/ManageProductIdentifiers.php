<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductVariantResource;
use App\Store\Models\Contracts\ProductVariant as ProductVariantContract;
use App\Support\Pages\BaseEditRecord;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components as SchemaComponents;

class ManageProductIdentifiers extends BaseEditRecord
{
    protected static string $resource = ProductResource::class;

    public ?string $sku = null;

    public ?string $gtin = null;

    public ?string $mpn = null;

    public ?string $ean = null;

    public function getTitle(): string|Htmlable
    {
        return __('admin::product.pages.identifiers.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::product.pages.identifiers.label');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return $parameters['record']->variants()->withTrashed()->count() == 1;
    }

    public function getBreadcrumb(): string
    {
        return __('admin::product.pages.identifiers.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-identifiers');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $variant = $this->getVariant();

        $this->sku = $variant->sku;
        $this->gtin = $variant->gtin;
        $this->mpn = $variant->mpn;
        $this->ean = $variant->ean;

    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variant = $this->getVariant();

        $variant->update($data);

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
        $variant = $this->getVariant();

        return $schema->components([
            SchemaComponents\Section::make()->schema([
                ProductVariantResource::getSkuFormComponent()
                    ->live()->unique(
                        table: fn () => $variant->getTable(),
                        ignorable: $variant,
                        ignoreRecord: true,
                    ),
                ProductVariantResource::getGtinFormComponent(),
                ProductVariantResource::getMpnFormComponent(),
                ProductVariantResource::getEanFormComponent(),
            ])->columns(1),
        ])->statePath('');
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
