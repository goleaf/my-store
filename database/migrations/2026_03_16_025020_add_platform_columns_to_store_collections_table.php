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
        Schema::table('store_collections', function (Blueprint $table) {
            $table->string('name')->nullable()->after('attribute_data');
            $table->string('slug')->nullable()->after('name');
            $table->string('image', 500)->nullable()->after('slug');
            $table->text('description')->nullable()->after('image');
            $table->boolean('is_featured')->default(false)->after('description');
            $table->boolean('is_active')->default(true)->after('is_featured');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('is_active');
            
            // SEO
            $table->string('meta_title')->nullable()->after('sort_order');
            $table->string('meta_description', 500)->nullable()->after('meta_title');

            $table->index('slug');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_collections', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'slug', 'image', 'description', 'is_featured', 'is_active', 'sort_order',
                'meta_title', 'meta_description'
            ]);
        });
    }
};
