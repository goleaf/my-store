<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('store_products', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->after('store_id')->constrained('store_collections'); // Mapping collection to category
            $table->string('name')->nullable()->after('category_id');
            $table->string('slug')->nullable()->after('name');
            $table->string('sku')->nullable()->after('slug');
            $table->string('short_description', 500)->nullable()->after('sku');
            $table->longText('description')->nullable()->after('short_description');
            $table->decimal('price', 10, 2)->default(0.00)->after('description');
            $table->decimal('original_price', 10, 2)->nullable()->after('price');
            $table->decimal('cost_price', 10, 2)->nullable()->after('original_price');
            $table->unsignedInteger('stock')->default(0)->after('cost_price');
            $table->unsignedInteger('low_stock_threshold')->default(5)->after('stock');
            $table->unsignedInteger('weight_grams')->nullable()->after('low_stock_threshold');
            $table->enum('badge', ['none', 'sale', 'hot', 'new'])->default('none')->after('weight_grams');
            $table->string('badge_custom', 50)->nullable()->after('badge');
            $table->string('availability_note')->nullable()->after('badge_custom');
            $table->string('shipping_info')->nullable()->after('availability_note');
            $table->string('product_type', 100)->nullable()->after('shipping_info');
            $table->string('product_code', 100)->nullable()->after('product_type');
            $table->string('seller_name')->nullable()->after('product_code');
            $table->string('units_per_pack', 100)->nullable()->after('seller_name');
            $table->text('disclaimer')->nullable()->after('units_per_pack');
            $table->boolean('is_active')->default(true)->after('disclaimer');
            $table->boolean('is_popular')->default(false)->after('is_active');
            $table->boolean('is_featured')->default(false)->after('is_popular');
            $table->boolean('is_daily_best')->default(false)->after('is_featured');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('is_daily_best');
            
            // SEO
            $table->string('meta_title')->nullable()->after('sort_order');
            $table->string('meta_description', 500)->nullable()->after('meta_title');
            $table->string('og_image', 500)->nullable()->after('meta_description');

            // Indexes
            $table->index('slug');
            $table->index('category_id');
            $table->index('is_popular');
            $table->index('is_featured');
            $table->index('price');
            $table->index('stock');
            
            // Fulltext
            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText(['name', 'short_description', 'description', 'sku'], 'products_fulltext');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_products', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropForeign(['category_id']);
            
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropIndex('products_fulltext');
            }
            
            $table->dropColumn([
                'store_id', 'category_id', 'name', 'slug', 'sku', 'short_description', 'description',
                'price', 'original_price', 'cost_price', 'stock', 'low_stock_threshold', 'weight_grams',
                'badge', 'badge_custom', 'availability_note', 'shipping_info', 'product_type', 'product_code',
                'seller_name', 'units_per_pack', 'disclaimer', 'is_active', 'is_popular', 'is_featured',
                'is_daily_best', 'sort_order', 'meta_title', 'meta_description', 'og_image'
            ]);
        });
    }
};
