<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductVariantResource;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class EditProductVariant extends BaseEditRecord
{
    protected static string $resource = ProductVariantResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('admin::productvariant.pages.edit.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::productvariant.pages.edit.title');
    }

    public static bool $formActionsAreSticky = true;

    public function getBreadcrumbs(): array
    {

        return ProductVariantResource::getBaseBreadcrumbs(
            $this->getRecord()
        );
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()->url(function (Model $record) {
            return ProductResource::getUrl('variants', [
                'record' => $record->product,
            ]);
        });
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $variant = $this->getRecord();

        if ($variant->mappedAttributes->isEmpty()) {
            redirect()->to(
                ProductVariantResource::getUrl('identifiers', [
                    'record' => $this->getRecord(),
                ])
            );
        }
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return $parameters['record']->mappedAttributes->isNotEmpty();
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
