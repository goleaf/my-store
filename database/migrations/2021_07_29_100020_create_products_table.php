<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable()->index();
            $table->unsignedBigInteger('store_id')->nullable()->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->foreignId('product_type_id')->constrained($this->prefix.'product_types');
            $table->string('status')->index();
            $table->json('attribute_data');
            $table->string('name')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00)->index();
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->unsignedInteger('stock')->default(0)->index();
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->unsignedInteger('weight_grams')->nullable();
            $table->string('badge')->default('none')->index();
            $table->string('badge_custom', 50)->nullable();
            $table->string('availability_note')->nullable();
            $table->string('shipping_info')->nullable();
            $table->string('product_type', 100)->nullable();
            $table->string('product_code', 100)->nullable();
            $table->string('seller_name')->nullable();
            $table->string('units_per_pack', 100)->nullable();
            $table->text('disclaimer')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_popular')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_daily_best')->default(false)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->decimal('rating', 3, 2)->nullable()->default(0);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('og_image', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText(['name', 'short_description', 'description', 'sku'], 'products_fulltext');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'products');
    }
};
