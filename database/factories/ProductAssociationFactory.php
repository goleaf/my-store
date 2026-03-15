<?php

namespace App\Store\Database\Factories;

use App\Store\Models\Product;
use App\Store\Models\ProductAssociation;

class ProductAssociationFactory extends BaseFactory
{
    protected $model = ProductAssociation::class;

    public function definition(): array
    {
        return [
            'product_parent_id' => Product::factory(),
            'product_target_id' => Product::factory(),
            'type' => 'cross-sell',
        ];
    }
}
