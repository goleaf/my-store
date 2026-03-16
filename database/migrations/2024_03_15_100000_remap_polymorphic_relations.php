<?php

use Illuminate\Support\Facades\Schema;
use App\Base\Migration;

class RemapPolymorphicRelations extends Migration
{
    public function up()
    {
        $modelClasses = collect([
            \App\Models\CartLine::class,
            \App\Models\ProductOption::class,
            \App\Models\Asset::class,
            \App\Models\Brand::class,
            \App\Models\TaxZone::class,
            \App\Models\TaxZoneCountry::class,
            \App\Models\TaxZoneCustomerGroup::class,
            \App\Models\DiscountCollection::class,
            \App\Models\TaxClass::class,
            \App\Models\ProductOptionValue::class,
            \App\Models\Channel::class,
            \App\Models\AttributeGroup::class,
            \App\Models\Tag::class,
            \App\Models\Cart::class,
            \App\Models\Collection::class,
            \App\Models\Discount::class,
            \App\Models\TaxRate::class,
            \App\Models\Price::class,
            \App\Models\Discountable::class,
            \App\Models\State::class,
            \App\Models\UserPermission::class,
            \App\Models\OrderAddress::class,
            \App\Models\Country::class,
            \App\Models\Address::class,
            \App\Models\Url::class,
            \App\Models\ProductVariant::class,
            \App\Models\TaxZonePostcode::class,
            \App\Models\ProductAssociation::class,
            \App\Models\TaxRateAmount::class,
            \App\Models\Attribute::class,
            \App\Models\Order::class,
            \App\Models\Customer::class,
            \App\Models\OrderLine::class,
            \App\Models\CartAddress::class,
            \App\Models\Language::class,
            \App\Models\TaxZoneState::class,
            \App\Models\Currency::class,
            \App\Models\Product::class,
            \App\Models\Transaction::class,
            \App\Models\ProductType::class,
            \App\Models\CollectionGroup::class,
            \App\Models\CustomerGroup::class,
        ])->mapWithKeys(
            fn ($class) => [
                $class => \App\Facades\ModelManifest::getMorphMapKey($class),
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

        $nonStoreTables = [
            'activity_log' => 'subject_type',
            'media' => 'model_type',
            'model_has_permissions' => 'model_type',
            'model_has_roles' => 'model_type',
        ];

        foreach ($modelClasses as $modelClass => $mapping) {

            foreach ($nonStoreTables as $table => $column) {
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
