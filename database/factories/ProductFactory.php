<?php

namespace App\Store\Database\Factories;

use App\Store\FieldTypes\Text;
use App\Store\Models\Brand;
use App\Store\Models\Product;
use App\Store\Models\ProductType;

class ProductFactory extends BaseFactory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_type_id' => ProductType::factory(),
            'status' => 'published',
            'brand_id' => Brand::factory()->create()->id,
            'attribute_data' => collect([
                'name' => new Text($this->faker->name),
                'description' => new Text($this->faker->sentence),
            ]),
        ];
    }
}
