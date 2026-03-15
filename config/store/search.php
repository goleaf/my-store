<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models for indexing
    |--------------------------------------------------------------------------
    |
    | The model listed here will be used to create/populate the indexes.
    | You can provide your own model here to run them all on the same
    | search engine.
    |
    */
    'models' => [
        /*
         * These models are required by the system, do not change them.
         */
        App\Store\Models\Brand::class,
        App\Store\Models\Collection::class,
        App\Store\Models\Customer::class,
        App\Store\Models\Order::class,
        App\Store\Models\Product::class,
        App\Store\Models\ProductOption::class,

        /*
         * Below you can add your own models for indexing...
         */
        // App\Models\Example::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Search engine mapping
    |--------------------------------------------------------------------------
    |
    | You can define what search driver each searchable model should use.
    | If the model isn't defined here, it will use the SCOUT_DRIVER env variable.
    |
    */
    'engine_map' => [
        // App\Store\Models\Product::class => 'algolia',
        // App\Store\Models\Order::class => 'meilisearch',
        // App\Store\Models\Collection::class => 'meilisearch',
    ],

    'indexers' => [
        App\Store\Models\Brand::class => App\Store\Search\BrandIndexer::class,
        App\Store\Models\Collection::class => App\Store\Search\CollectionIndexer::class,
        App\Store\Models\Customer::class => App\Store\Search\CustomerIndexer::class,
        App\Store\Models\Order::class => App\Store\Search\OrderIndexer::class,
        App\Store\Models\Product::class => App\Store\Search\ProductIndexer::class,
        App\Store\Models\ProductOption::class => App\Store\Search\ProductOptionIndexer::class,
    ],

];
