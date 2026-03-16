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
        Schema::table('store_product_variants', function (Blueprint $table) {
            $table->string('label')->nullable()->after('product_id'); // e.g. "250g", "500g"
            $table->decimal('price_override', 10, 2)->nullable()->after('label');
            $table->decimal('price_modifier', 10, 2)->default(0.00)->after('price_override');
            $table->decimal('original_price_override', 10, 2)->nullable()->after('price_modifier');
            $table->unsignedInteger('stock')->default(0)->after('original_price_override');
            $table->unsignedTinyInteger('sort_order')->default(0)->after('stock');
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_product_variants', function (Blueprint $table) {
            $table->dropColumn([
                'label', 'price_override', 'price_modifier', 'original_price_override',
                'stock', 'sort_order', 'is_active'
            ]);
        });
    }
};
