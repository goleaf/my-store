<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductVariantResource\Pages\ManageVariantInventory;
use App\Store\Models\Contracts\ProductVariant as ProductVariantContract;
use App\Support\Pages\BaseEditRecord;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class ManageProductInventory extends BaseEditRecord
{
    protected static string $resource = ProductResource::class;

    public ?string $stock = null;

    public ?string $backorder = null;

    public ?string $purchasable = null;

    public ?int $unit_quantity = 1;

    public ?int $quantity_increment = 1;

    public ?int $min_quantity = 1;

    public function getTitle(): string|Htmlable
    {
        return __('admin::product.pages.inventory.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::product.pages.inventory.label');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return $parameters['record']->variants()->withTrashed()->count() == 1;
    }

    public function getBreadcrumb(): string
    {
        return __('admin::product.pages.inventory.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-inventory');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $variant = $this->getVariant();

        $this->stock = $variant->stock;
        $this->backorder = $variant->backorder;
        $this->purchasable = $variant->purchasable;
        $this->unit_quantity = $variant->unit_quantity;
        $this->min_quantity = $variant->min_quantity;
        $this->quantity_increment = $variant->quantity_increment;
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

    public function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return (new ManageVariantInventory)->form($schema)->statePath('');
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
