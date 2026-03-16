<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'order_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained($this->prefix.'orders');
            $table->morphs('purchasable');
            $table->string('type')->index();
            $table->string('description');
            $table->string('option')->nullable();
            $table->string('identifier')->index();
            $table->string('product_name')->nullable();
            $table->string('variant_label')->nullable();
            $table->string('product_sku')->nullable();
            $table->string('product_image', 500)->nullable();
            $table->unsignedBigInteger('unit_price')->index();
            $table->smallInteger('unit_quantity')->default(1)->unsigned()->index();
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('sub_total')->index();
            $table->unsignedBigInteger('discount_total')->default(0)->index();
            $table->json('tax_breakdown');
            $table->unsignedBigInteger('tax_total')->index();
            $table->unsignedBigInteger('total')->index();
            $table->unsignedInteger('returned_qty')->default(0);
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'order_lines');
    }
};
