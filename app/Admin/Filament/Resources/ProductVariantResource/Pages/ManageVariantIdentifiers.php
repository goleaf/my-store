<?php

namespace App\Admin\Filament\Resources\ProductVariantResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use App\Admin\Filament\Resources\ProductResource;
use App\Admin\Filament\Resources\ProductVariantResource;
use App\Admin\Support\Pages\BaseEditRecord;

class ManageVariantIdentifiers extends BaseEditRecord
{
    protected static string $resource = ProductVariantResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('admin::productvariant.pages.identifiers.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::productvariant.pages.identifiers.title');
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
            ProductVariantResource::getUrl('identifiers', [
                'record' => $this->getRecord(),
            ]) => $this->getTitle(),
        ];
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::product-identifiers');
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([
            Section::make()->schema([
                ProductVariantResource::getSkuFormComponent()
                    ->live()->unique(
                        table: fn () => $this->getRecord()->getTable(),
                        ignorable: $this->getRecord(),
                        ignoreRecord: true,
                    ),
                ProductVariantResource::getGtinFormComponent(),
                ProductVariantResource::getMpnFormComponent(),
                ProductVariantResource::getEanFormComponent(),
            ])->columns(1),
        ]);
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            ProductVariantResource::getVariantSwitcherWidget(
                $this->getRecord()
            ),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
