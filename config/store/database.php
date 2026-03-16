<?php

return [

    'connection' => null,

    'table_prefix' => 'store_',

    /*
    |--------------------------------------------------------------------------
    | Morph Prefix
    |--------------------------------------------------------------------------
    |
    | If you wish to prefix Store's morph mapping in the database, you can
    | set that here e.g. `store_product` instead of `product`
    |
    */
    'morph_prefix' => null,

    /*
    |--------------------------------------------------------------------------
    | Users Table ID
    |--------------------------------------------------------------------------
    |
    | Store adds a relationship to your 'users' table and by default assumes
    | a 'bigint'. You can change this to either an 'int' or 'uuid'.
    |
    */
    'users_id_type' => 'bigint',

    /*
    |--------------------------------------------------------------------------
    | Disable migrations
    |--------------------------------------------------------------------------
    |
    | Prevent Store`s default package migrations from running for the core.
    | Set to 'true' to disable.
    |
    */
    'disable_migrations' => false,

];
