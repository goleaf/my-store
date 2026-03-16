<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Facades\DB;
use App\Models\Attribute;
use App\Models\Currency;
use App\Models\Product;
use App\Models\TaxClass;
use App\Support\Pages\BaseListRecords;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Schemas\Components as SchemaComponents;

class ListProducts extends BaseListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false)->form(
                static::createActionFormInputs()
            )->using(
                fn (array $data, string $model) => static::createRecord($data, $model)
            )->successRedirectUrl(fn (Model $record): string => ProductResource::getUrl('edit', [
                'record' => $record,
            ])),
        ];
    }

    public static function createActionFormInputs(): array
    {
        return [
            SchemaComponents\Grid::make(2)->schema([
                ProductResource::getBaseNameFormComponent(),
                ProductResource::getProductTypeFormComponent()->required(),
            ]),
            SchemaComponents\Grid::make(2)->schema([
                ProductResource::getSkuFormComponent(),
                ProductResource::getBasePriceFormComponent(),
            ]),
        ];
    }

    public static function createRecord(array $data, string $model): Model
    {
        $currency = Currency::getDefault();

        $nameAttribute = Attribute::whereAttributeType(
            $model::morphName()
        )
            ->whereHandle('name')
            ->first()
            ->type;

        DB::beginTransaction();
        $product = $model::create([
            'status' => 'draft',
            'product_type_id' => $data['product_type_id'],
            'attribute_data' => [
                'name' => new $nameAttribute($data['name']),
            ],
        ]);
        $variant = $product->variants()->create([
            'tax_class_id' => TaxClass::getDefault()->id,
            'sku' => $data['sku'],
        ]);
        $variant->prices()->create([
            'min_quantity' => 1,
            'currency_id' => $currency->id,
            'price' => (int) bcmul($data['base_price'], $currency->factor),
        ]);
        DB::commit();

        return $product;
    }

    public function getDefaultTabs(): array
    {
        return [
            'all' => Tab::make(__('admin::product.tabs.all')),
            'published' => Tab::make(__('admin::product.tabs.published'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'published')),
            'draft' => Tab::make(__('admin::product.tabs.draft'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft'))
                ->badge(Product::query()->where('status', 'draft')->count()),
        ];
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
