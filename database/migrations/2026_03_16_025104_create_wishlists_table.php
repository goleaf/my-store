<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('store_customers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('store_products')->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('store_product_variants')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['customer_id', 'product_id', 'variant_id'], 'unique_wishlist');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
