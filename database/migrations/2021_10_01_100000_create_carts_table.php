<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('customer_id')->nullable()->constrained($this->prefix.'customers')->nullOnDelete();
            $table->foreignId('merged_id')->nullable()->constrained($this->prefix.'carts')->nullOnDelete();
            $table->foreignId('currency_id')->constrained($this->prefix.'currencies');
            $table->foreignId('channel_id')->constrained($this->prefix.'channels');
            $table->foreignId('order_id')->nullable()->constrained($this->prefix.'orders')->nullOnDelete();
            $table->string('coupon_code')->index()->nullable();
            $table->string('session_id')->nullable()->index();
            $table->unsignedBigInteger('coupon_id')->nullable()->index();
            $table->unsignedBigInteger('zone_id')->nullable()->index();
            $table->dateTime('completed_at')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'carts');
    }
};
