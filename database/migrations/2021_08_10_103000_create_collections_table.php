<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_group_id')->constrained($this->prefix.'collection_groups');
            $table->nestedSet();
            $table->string('type')->default('static')->index();
            $table->json('attribute_data');
            $table->string('name')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->string('image', 500)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('sort')->default('custom')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'collections');
    }
};
