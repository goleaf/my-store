<?php

use App\Actions\Carts\GenerateFingerprint;

return [
    /*
    |--------------------------------------------------------------------------
    | Fingerprint Generator
    |--------------------------------------------------------------------------
    |
    | Specify which class should be used when generating a cart fingerprint.
    |
    */
    'fingerprint_generator' => GenerateFingerprint::class,

    /*
    |--------------------------------------------------------------------------
    | Authentication policy
    |--------------------------------------------------------------------------
    |
    | When a user logs in, by default, Store will merge the current (guest) cart
    | with the users current cart, if they have one.
    | Available options: 'merge', 'override'
    |
    */
    'auth_policy' => 'merge',

    /*
    |--------------------------------------------------------------------------
    | Cart Pipelines
    |--------------------------------------------------------------------------
    |
    | Define which pipelines should be run when performing cart calculations.
    | The default ones provided should suit most needs, however you are
    | free to add your own as you see fit.
    |
    | Each pipeline class will be run from top to bottom.
    |
    */
    'pipelines' => [
        /*
         * Run these pipelines when the cart is calculating.
        */
        'cart' => [
            App\Pipelines\Cart\CalculateLines::class,
            App\Pipelines\Cart\ApplyShipping::class,
            App\Pipelines\Cart\ApplyDiscounts::class,
            App\Pipelines\Cart\CalculateTax::class,
            App\Pipelines\Cart\Calculate::class,
        ],

        /*
         * Run these pipelines when the cart lines are being calculated.
        */
        'cart_lines' => [
            App\Pipelines\CartLine\GetUnitPrice::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cart Actions
    |--------------------------------------------------------------------------
    |
    | Here you can decide what action should be run during a Carts lifecycle.
    | The default actions should be fine for most cases.
    |
    */
    'actions' => [
        'add_to_cart' => App\Actions\Carts\AddOrUpdatePurchasable::class,
        'get_existing_cart_line' => App\Actions\Carts\GetExistingCartLine::class,
        'update_cart_line' => App\Actions\Carts\UpdateCartLine::class,
        'remove_from_cart' => App\Actions\Carts\RemovePurchasable::class,
        'add_address' => App\Actions\Carts\AddAddress::class,
        'set_shipping_option' => App\Actions\Carts\SetShippingOption::class,
        'order_create' => App\Actions\Carts\CreateOrder::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cart Action Validators
    |--------------------------------------------------------------------------
    |
    | You may wish to provide additional validation when actions executed on
    | the cart model. The defaults provided should be enough for most cases.
    |
    */
    'validators' => [

        'add_to_cart' => [
            App\Validation\CartLine\CartLineQuantity::class,
            App\Validation\CartLine\CartLineStock::class,
        ],

        'update_cart_line' => [
            App\Validation\CartLine\CartLineQuantity::class,
            App\Validation\CartLine\CartLineStock::class,
        ],

        'remove_from_cart' => [],

        'set_shipping_option' => [
            App\Validation\Cart\ShippingOptionValidator::class,
        ],

        'order_create' => [
            App\Validation\Cart\ValidateCartForOrderCreation::class,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default eager loading
    |--------------------------------------------------------------------------
    |
    | When loading up a cart and doing calculations, there's a few relationships
    | that are used when it's running. Here you can define which relationships
    | should be eager loaded when these calculations take place.
    |
    */
    'eager_load' => [
        'currency',
        'lines.purchasable.taxClass',
        'lines.purchasable.values',
        'lines.purchasable.product.thumbnail',
        'lines.purchasable.prices.currency',
        'lines.purchasable.prices.priceable',
        'lines.purchasable.product',
        'lines.cart.currency',
    ],

    /*
    |--------------------------------------------------------------------------
    | Prune carts
    |--------------------------------------------------------------------------
    |
    | Should the cart models be pruned to prevent data build up and
    | some settings controlling how pruning should be determined
    |
    */
    'prune_tables' => [

        'enabled' => false,

        'pipelines' => [
            App\Pipelines\CartPrune\PruneAfter::class,
            App\Pipelines\CartPrune\WithoutOrders::class,
            App\Pipelines\CartPrune\WhereNotMerged::class,
        ],

        'prune_interval' => 90, // days

    ],
];
