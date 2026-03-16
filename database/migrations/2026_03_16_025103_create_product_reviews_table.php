<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('store_products')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('store_customers')->onDelete('cascade');
            $table->unsignedTinyInteger('rating');
            $table->unsignedTinyInteger('rating_flavor')->nullable();
            $table->unsignedTinyInteger('rating_value')->nullable();
            $table->unsignedTinyInteger('rating_scent')->nullable();
            $table->string('title')->nullable();
            $table->text('body');
            $table->json('images')->nullable();
            $table->unsignedInteger('helpful_count')->default(0);
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_flagged')->default(false);
            $table->text('admin_reply')->nullable();
            $table->timestamp('admin_replied_at')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'customer_id'], 'no_dup_review');
            $table->index('product_id');
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
