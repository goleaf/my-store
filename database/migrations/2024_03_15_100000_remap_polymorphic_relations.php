<?php

use Illuminate\Support\Facades\Schema;
use App\Store\Base\Migration;

class RemapPolymorphicRelations extends Migration
{
    public function up()
    {
        $modelClasses = collect([
            \App\Store\Models\CartLine::class,
            \App\Store\Models\ProductOption::class,
            \App\Store\Models\Asset::class,
            \App\Store\Models\Brand::class,
            \App\Store\Models\TaxZone::class,
            \App\Store\Models\TaxZoneCountry::class,
            \App\Store\Models\TaxZoneCustomerGroup::class,
            \App\Store\Models\DiscountCollection::class,
            \App\Store\Models\TaxClass::class,
            \App\Store\Models\ProductOptionValue::class,
            \App\Store\Models\Channel::class,
            \App\Store\Models\AttributeGroup::class,
            \App\Store\Models\Tag::class,
            \App\Store\Models\Cart::class,
            \App\Store\Models\Collection::class,
            \App\Store\Models\Discount::class,
            \App\Store\Models\TaxRate::class,
            \App\Store\Models\Price::class,
            \App\Store\Models\Discountable::class,
            \App\Store\Models\State::class,
            \App\Store\Models\UserPermission::class,
            \App\Store\Models\OrderAddress::class,
            \App\Store\Models\Country::class,
            \App\Store\Models\Address::class,
            \App\Store\Models\Url::class,
            \App\Store\Models\ProductVariant::class,
            \App\Store\Models\TaxZonePostcode::class,
            \App\Store\Models\ProductAssociation::class,
            \App\Store\Models\TaxRateAmount::class,
            \App\Store\Models\Attribute::class,
            \App\Store\Models\Order::class,
            \App\Store\Models\Customer::class,
            \App\Store\Models\OrderLine::class,
            \App\Store\Models\CartAddress::class,
            \App\Store\Models\Language::class,
            \App\Store\Models\TaxZoneState::class,
            \App\Store\Models\Currency::class,
            \App\Store\Models\Product::class,
            \App\Store\Models\Transaction::class,
            \App\Store\Models\ProductType::class,
            \App\Store\Models\CollectionGroup::class,
            \App\Store\Models\CustomerGroup::class,
        ])->mapWithKeys(
            fn ($class) => [
                $class => \App\Store\Facades\ModelManifest::getMorphMapKey($class),
            ]
        );

        $tables = [
            'attributables' => ['attributable_type'],
            'attributes' => ['attribute_type'],
            'attribute_groups' => ['attributable_type'],
            'cart_lines' => ['purchasable_type'],
            'channelables' => ['channelable_type'],
            'discount_purchasables' => ['purchasable_type'],
            'order_lines' => ['purchasable_type'],
            'prices' => ['priceable_type'],
            'taggables' => ['taggable_type'],
            'urls' => ['element_type'],
        ];

        $nonLunarTables = [
            'activity_log' => 'subject_type',
            'media' => 'model_type',
            'model_has_permissions' => 'model_type',
            'model_has_roles' => 'model_type',
        ];

        foreach ($modelClasses as $modelClass => $mapping) {

            foreach ($nonLunarTables as $table => $column) {
                if (! Schema::hasTable($table)) {
                    continue;
                }
                \Illuminate\Support\Facades\DB::table($table)
                    ->where($column, '=', $modelClass)
                    ->update([
                        $column => $mapping,
                    ]);
            }

            foreach ($tables as $tableName => $columns) {
                $table = \Illuminate\Support\Facades\DB::table(
                    $this->prefix.$tableName
                );

                foreach ($columns as $column) {
                    $table->where($column, '=', $modelClass)->update([
                        $column => $mapping,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        // ...
    }
}
