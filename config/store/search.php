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
        App\Models\Brand::class,
        App\Models\Collection::class,
        App\Models\Customer::class,
        App\Models\Order::class,
        App\Models\Product::class,
        App\Models\ProductOption::class,

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
        // App\Models\Product::class => 'algolia',
        // App\Models\Order::class => 'meilisearch',
        // App\Models\Collection::class => 'meilisearch',
    ],

    'indexers' => [
        App\Models\Brand::class => App\Search\BrandIndexer::class,
        App\Models\Collection::class => App\Search\CollectionIndexer::class,
        App\Models\Customer::class => App\Search\CustomerIndexer::class,
        App\Models\Order::class => App\Search\OrderIndexer::class,
        App\Models\Product::class => App\Search\ProductIndexer::class,
        App\Models\ProductOption::class => App\Search\ProductOptionIndexer::class,
    ],

];
