<?php

use Illuminate\Support\Facades\Schema;
use App\Base\Migration;
use App\Facades\ModelManifest;
use App\Models\Address;
use App\Models\Asset;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\CartAddress;
use App\Models\CartLine;
use App\Models\Channel;
use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Discount;
use App\Models\DiscountCollection;
use App\Models\Discountable;
use App\Models\Language;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderLine;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductAssociation;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductType;
use App\Models\ProductVariant;
use App\Models\State;
use App\Models\Tag;
use App\Models\TaxClass;
use App\Models\TaxRate;
use App\Models\TaxRateAmount;
use App\Models\TaxZone;
use App\Models\TaxZoneCountry;
use App\Models\TaxZoneCustomerGroup;
use App\Models\TaxZonePostcode;
use App\Models\TaxZoneState;
use App\Models\Transaction;
use App\Models\Url;
use App\Models\UserPermission;
use Illuminate\Support\Facades\DB;

class RemapPolymorphicRelations extends Migration
{
    public function up()
    {
        $modelClasses = collect([
            CartLine::class,
            ProductOption::class,
            Asset::class,
            Brand::class,
            TaxZone::class,
            TaxZoneCountry::class,
            TaxZoneCustomerGroup::class,
            DiscountCollection::class,
            TaxClass::class,
            ProductOptionValue::class,
            Channel::class,
            AttributeGroup::class,
            Tag::class,
            Cart::class,
            Collection::class,
            Discount::class,
            TaxRate::class,
            Price::class,
            Discountable::class,
            State::class,
            UserPermission::class,
            OrderAddress::class,
            Country::class,
            Address::class,
            Url::class,
            ProductVariant::class,
            TaxZonePostcode::class,
            ProductAssociation::class,
            TaxRateAmount::class,
            Attribute::class,
            Order::class,
            Customer::class,
            OrderLine::class,
            CartAddress::class,
            Language::class,
            TaxZoneState::class,
            Currency::class,
            Product::class,
            Transaction::class,
            ProductType::class,
            CollectionGroup::class,
            CustomerGroup::class,
        ])->mapWithKeys(
            fn ($class) => [
                $class => ModelManifest::getMorphMapKey($class),
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
                DB::table($table)
                    ->where($column, '=', $modelClass)
                    ->update([
                        $column => $mapping,
                    ]);
            }

            foreach ($tables as $tableName => $columns) {
                $table = DB::table(
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
