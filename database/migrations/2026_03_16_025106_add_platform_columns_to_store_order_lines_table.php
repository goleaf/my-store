<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_order_lines', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('purchasable_id');
            $table->string('variant_label')->nullable()->after('product_name');
            $table->string('product_sku')->nullable()->after('variant_label');
            $table->string('product_image', 500)->nullable()->after('product_sku');
            $table->unsignedInteger('returned_qty')->default(0)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('store_order_lines', function (Blueprint $table) {
            $table->dropColumn(['product_name', 'variant_label', 'product_sku', 'product_image', 'returned_qty']);
        });
    }
};
