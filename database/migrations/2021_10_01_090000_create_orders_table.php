<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->prefix.'orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('customer_id')->nullable()->constrained($this->prefix.'customers')->nullOnDelete();
            $table->unsignedBigInteger('cart_id')->nullable()->index();
            $table->foreignId('channel_id')->constrained($this->prefix.'channels');
            $table->boolean('new_customer')->default(false)->index();
            $table->string('status')->index();
            $table->string('reference')->nullable()->unique();
            $table->string('customer_reference')->nullable();
            $table->unsignedBigInteger('sub_total')->index();
            $table->json('discount_breakdown')->nullable();
            $table->unsignedBigInteger('discount_total')->default(0)->index();
            $table->json('shipping_breakdown')->nullable();
            $table->unsignedBigInteger('shipping_total')->default(0)->index();
            $table->json('tax_breakdown');
            $table->unsignedBigInteger('tax_total')->index();
            $table->unsignedBigInteger('total')->index();
            $table->text('notes')->nullable();
            $table->string('currency_code', 3);
            $table->string('compare_currency_code', 3)->nullable();
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->dateTime('placed_at')->nullable()->index();
            $table->string('fingerprint')->nullable()->index();
            $table->string('order_number', 20)->nullable()->unique();
            $table->string('shipping_phone', 20)->nullable();
            $table->string('shipping_zip', 20)->nullable();
            $table->string('delivery_slot_label')->nullable();
            $table->date('delivery_date')->nullable();
            $table->time('delivery_start_time')->nullable();
            $table->time('delivery_end_time')->nullable();
            $table->text('delivery_instructions')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('payment_reference')->nullable();
            $table->decimal('items_subtotal', 10, 2)->default(0.00);
            $table->decimal('service_fee', 10, 2)->default(0.00);
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->string('tracking_number')->nullable();
            $table->string('tracking_url', 500)->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'orders');
    }
};
