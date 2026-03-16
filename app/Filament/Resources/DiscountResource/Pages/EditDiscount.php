<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Base\AdminPanelDiscountInterface;
use App\Filament\Resources\DiscountResource;
use App\DiscountTypes\BuyXGetY;
use App\Models\Currency;
use App\Support\Pages\BaseEditRecord;
use Filament\Actions;
use Filament\Resources\RelationManagers\RelationGroup;

class EditDiscount extends BaseEditRecord
{
    protected static string $resource = DiscountResource::class;

    public function getTitle(): string
    {
        return __('admin::discount.pages.edit.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::discount.pages.edit.title');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (class_exists($data['type'])) {
            $type = new $data['type'];

            if ($type instanceof AdminPanelDiscountInterface) {
                return $type->adminPanelOnFill($data);
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (class_exists($data['type'])) {
            $type = new $data['type'];

            if ($type instanceof AdminPanelDiscountInterface) {
                return $type->adminPanelOnSave($data);
            }
        }

        $minPrices = $data['data']['min_prices'] ?? [];
        $fixedPrices = $data['data']['fixed_values'] ?? [];
        $currencies = Currency::enabled()->get();

        foreach ($minPrices as $currencyCode => $value) {
            $currency = $currencies->first(
                fn ($currency) => $currency->code == $currencyCode
            );

            if (! $currency) {
                continue;
            }
            $data['data']['min_prices'][$currencyCode] = (int) round($value * $currency->factor);
        }

        foreach ($fixedPrices as $currencyCode => $fixedPrice) {
            $currency = $currencies->first(
                fn ($currency) => $currency->code == $currencyCode
            );

            if (! $currency) {
                continue;
            }
            $data['data']['fixed_values'][$currencyCode] = (int) round($fixedPrice * $currency->factor);
        }

        return $data;
    }

    public function getRelationManagers(): array
    {
        $managers = [];

        if ($this->record->type == BuyXGetY::class) {
            $managers[] = RelationGroup::make('Conditions', [
                DiscountResource\RelationManagers\ProductConditionRelationManager::class,
                DiscountResource\RelationManagers\CollectionConditionRelationManager::class,
            ]);
            $managers[] = DiscountResource\RelationManagers\ProductRewardRelationManager::class;
        }

        $type = $this->record->getType();
        if ($type instanceof AdminPanelDiscountInterface) {
            $managers = array_merge($managers, $type->adminPanelRelationManagers());
        }

        return $managers;
    }
}
