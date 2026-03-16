<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'lunar_addresses' => 'store_addresses',
            'lunar_assets' => 'store_assets',
            'lunar_attributables' => 'store_attributables',
            'lunar_attribute_groups' => 'store_attribute_groups',
            'lunar_attributes' => 'store_attributes',
            'lunar_brand_collection' => 'store_brand_collection',
            'lunar_brand_discount' => 'store_brand_discount',
            'lunar_brands' => 'store_brands',
            'lunar_cart_addresses' => 'store_cart_addresses',
            'lunar_cart_line_discount' => 'store_cart_line_discount',
            'lunar_cart_lines' => 'store_cart_lines',
            'lunar_carts' => 'store_carts',
            'lunar_channelables' => 'store_channelables',
            'lunar_channels' => 'store_channels',
            'lunar_collection_customer_group' => 'store_collection_customer_group',
            'lunar_collection_discount' => 'store_collection_discount',
            'lunar_collection_groups' => 'store_collection_groups',
            'lunar_collection_product' => 'store_collection_product',
            'lunar_collections' => 'store_collections',
            'lunar_countries' => 'store_countries',
            'lunar_country_shipping_zone' => 'store_country_shipping_zone',
            'lunar_currencies' => 'store_currencies',
            'lunar_customer_customer_group' => 'store_customer_customer_group',
            'lunar_customer_discount' => 'store_customer_discount',
            'lunar_customer_group_discount' => 'store_customer_group_discount',
            'lunar_customer_group_product' => 'store_customer_group_product',
            'lunar_customer_group_shipping_method' => 'store_customer_group_shipping_method',
            'lunar_customer_groups' => 'store_customer_groups',
            'lunar_customer_user' => 'store_customer_user',
            'lunar_customers' => 'store_customers',
            'lunar_discount_user' => 'store_discount_user',
            'lunar_discountables' => 'store_discountables',
            'lunar_discounts' => 'store_discounts',
            'lunar_exclusion_list_shipping_zone' => 'store_exclusion_list_shipping_zone',
            'lunar_languages' => 'store_languages',
            'lunar_media_product_variant' => 'store_media_product_variant',
            'lunar_order_addresses' => 'store_order_addresses',
            'lunar_order_lines' => 'store_order_lines',
            'lunar_order_shipping_zone' => 'store_order_shipping_zone',
            'lunar_orders' => 'store_orders',
            'lunar_prices' => 'store_prices',
            'lunar_product_associations' => 'store_product_associations',
            'lunar_product_option_value_product_variant' => 'store_product_option_value_product_variant',
            'lunar_product_option_values' => 'store_product_option_values',
            'lunar_product_options' => 'store_product_options',
            'lunar_product_product_option' => 'store_product_product_option',
            'lunar_product_types' => 'store_product_types',
            'lunar_product_variants' => 'store_product_variants',
            'lunar_products' => 'store_products',
            'lunar_shipping_exclusion_lists' => 'store_shipping_exclusion_lists',
            'lunar_shipping_exclusions' => 'store_shipping_exclusions',
            'lunar_shipping_methods' => 'store_shipping_methods',
            'lunar_shipping_rates' => 'store_shipping_rates',
            'lunar_shipping_zone_postcodes' => 'store_shipping_zone_postcodes',
            'lunar_shipping_zones' => 'store_shipping_zones',
            'lunar_staff' => 'store_staff',
            'lunar_state_shipping_zone' => 'store_state_shipping_zone',
            'lunar_states' => 'store_states',
            'lunar_stripe_payment_intents' => 'store_stripe_payment_intents',
            'lunar_taggables' => 'store_taggables',
            'lunar_tags' => 'store_tags',
            'lunar_tax_classes' => 'store_tax_classes',
            'lunar_tax_rate_amounts' => 'store_tax_rate_amounts',
            'lunar_tax_rates' => 'store_tax_rates',
            'lunar_tax_zone_countries' => 'store_tax_zone_countries',
            'lunar_tax_zone_customer_groups' => 'store_tax_zone_customer_groups',
            'lunar_tax_zone_postcodes' => 'store_tax_zone_postcodes',
            'lunar_tax_zone_states' => 'store_tax_zone_states',
            'lunar_tax_zones' => 'store_tax_zones',
            'lunar_transactions' => 'store_transactions',
            'lunar_urls' => 'store_urls',
        ];

        foreach ($tables as $oldName => $newName) {
            if (Schema::hasTable($oldName) && !Schema::hasTable($newName)) {
                Schema::rename($oldName, $newName);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'store_addresses' => 'lunar_addresses',
            'store_assets' => 'lunar_assets',
            'store_attributables' => 'lunar_attributables',
            'store_attribute_groups' => 'lunar_attribute_groups',
            'store_attributes' => 'lunar_attributes',
            'store_brand_collection' => 'lunar_brand_collection',
            'store_brand_discount' => 'lunar_brand_discount',
            'store_brands' => 'lunar_brands',
            'store_cart_addresses' => 'lunar_cart_addresses',
            'store_cart_line_discount' => 'lunar_cart_line_discount',
            'store_cart_lines' => 'lunar_cart_lines',
            'store_carts' => 'lunar_carts',
            'store_channelables' => 'lunar_channelables',
            'store_channels' => 'lunar_channels',
            'store_collection_customer_group' => 'lunar_collection_customer_group',
            'store_collection_discount' => 'lunar_collection_discount',
            'store_collection_groups' => 'lunar_collection_groups',
            'store_collection_product' => 'lunar_collection_product',
            'store_collections' => 'lunar_collections',
            'store_countries' => 'lunar_countries',
            'store_country_shipping_zone' => 'lunar_country_shipping_zone',
            'store_currencies' => 'lunar_currencies',
            'store_customer_customer_group' => 'lunar_customer_customer_group',
            'store_customer_discount' => 'lunar_customer_discount',
            'store_customer_group_discount' => 'lunar_customer_group_discount',
            'store_customer_group_product' => 'lunar_customer_group_product',
            'store_customer_group_shipping_method' => 'lunar_customer_group_shipping_method',
            'store_customer_groups' => 'lunar_customer_groups',
            'store_customer_user' => 'lunar_customer_user',
            'store_customers' => 'lunar_customers',
            'store_discount_user' => 'lunar_discount_user',
            'store_discountables' => 'lunar_discountables',
            'store_discounts' => 'lunar_discounts',
            'store_exclusion_list_shipping_zone' => 'lunar_exclusion_list_shipping_zone',
            'store_languages' => 'lunar_languages',
            'store_media_product_variant' => 'lunar_media_product_variant',
            'store_order_addresses' => 'lunar_order_addresses',
            'store_order_lines' => 'lunar_order_lines',
            'store_order_shipping_zone' => 'lunar_order_shipping_zone',
            'store_orders' => 'lunar_orders',
            'store_prices' => 'lunar_prices',
            'store_product_associations' => 'lunar_product_associations',
            'store_product_option_value_product_variant' => 'lunar_product_option_value_product_variant',
            'store_product_option_values' => 'lunar_product_option_values',
            'store_product_options' => 'lunar_product_options',
            'store_product_product_option' => 'lunar_product_product_option',
            'store_product_types' => 'lunar_product_types',
            'store_product_variants' => 'lunar_product_variants',
            'store_products' => 'lunar_products',
            'store_shipping_exclusion_lists' => 'lunar_shipping_exclusion_lists',
            'store_shipping_exclusions' => 'lunar_shipping_exclusions',
            'store_shipping_methods' => 'lunar_shipping_methods',
            'store_shipping_rates' => 'lunar_shipping_rates',
            'store_shipping_zone_postcodes' => 'lunar_shipping_zone_postcodes',
            'store_shipping_zones' => 'lunar_shipping_zones',
            'store_staff' => 'lunar_staff',
            'store_state_shipping_zone' => 'lunar_state_shipping_zone',
            'store_states' => 'lunar_states',
            'store_stripe_payment_intents' => 'lunar_stripe_payment_intents',
            'store_taggables' => 'lunar_taggables',
            'store_tags' => 'lunar_tags',
            'store_tax_classes' => 'lunar_tax_classes',
            'store_tax_rate_amounts' => 'lunar_tax_rate_amounts',
            'store_tax_rates' => 'lunar_tax_rates',
            'store_tax_zone_countries' => 'lunar_tax_zone_countries',
            'store_tax_zone_customer_groups' => 'lunar_tax_zone_customer_groups',
            'store_tax_zone_postcodes' => 'lunar_tax_zone_postcodes',
            'store_tax_zone_states' => 'lunar_tax_zone_states',
            'store_tax_zones' => 'lunar_tax_zones',
            'store_transactions' => 'lunar_transactions',
            'store_urls' => 'lunar_urls',
        ];

        foreach ($tables as $oldName => $newName) {
            if (Schema::hasTable($oldName) && !Schema::hasTable($newName)) {
                Schema::rename($oldName, $newName);
            }
        }
    }
};
