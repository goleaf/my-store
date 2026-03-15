<?php

namespace App\Store\Database\Factories;

use Illuminate\Support\Str;
use App\Store\Models\Product;
use App\Store\Models\ProductVariant;
use App\Store\Models\TaxClass;
use App\Store\Models\TaxRateAmount;

class ProductVariantFactory extends BaseFactory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'tax_class_id' => TaxClass::factory()->hasTaxRateAmounts(
                TaxRateAmount::factory()
            ),
            'sku' => Str::random(12),
            'unit_quantity' => 1,
            'gtin' => $this->faker->unique()->isbn13,
            'mpn' => $this->faker->unique()->isbn13,
            'ean' => $this->faker->unique()->ean13,
            'shippable' => true,
        ];
    }
}
