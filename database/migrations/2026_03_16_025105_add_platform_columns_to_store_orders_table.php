<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_orders', function (Blueprint $table) {
            $table->string('order_number', 20)->nullable()->unique()->after('id');
            $table->string('shipping_phone', 20)->nullable()->after('shipping_address_id');
            $table->string('shipping_zip', 20)->nullable()->after('shipping_address_id');
            $table->string('delivery_slot_label')->nullable()->after('status');
            $table->date('delivery_date')->nullable()->after('delivery_slot_label');
            $table->time('delivery_start_time')->nullable()->after('delivery_date');
            $table->time('delivery_end_time')->nullable()->after('delivery_start_time');
            $table->text('delivery_instructions')->nullable()->after('delivery_end_time');
            $table->enum('payment_method', ['stripe', 'paypal', 'payoneer', 'cod'])->nullable()->after('delivery_instructions');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('pending')->after('payment_method');
            $table->string('payment_reference')->nullable()->after('payment_status');
            $table->decimal('items_subtotal', 10, 2)->default(0.00)->after('payment_reference');
            $table->decimal('service_fee', 10, 2)->default(0.00)->after('items_subtotal');
            $table->decimal('delivery_fee', 10, 2)->default(0.00)->after('service_fee');
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('delivery_fee');
            $table->string('tracking_number')->nullable()->after('status');
            $table->string('tracking_url', 500)->nullable()->after('tracking_number');
            $table->text('internal_notes')->nullable()->after('tracking_url');
            $table->text('cancellation_reason')->nullable()->after('internal_notes');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            $table->timestamp('delivered_at')->nullable()->after('cancelled_at');

            $table->index('order_number');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('store_orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number', 'shipping_phone', 'shipping_zip', 'delivery_slot_label',
                'delivery_date', 'delivery_start_time', 'delivery_end_time', 'delivery_instructions',
                'payment_method', 'payment_status', 'payment_reference', 'items_subtotal',
                'service_fee', 'delivery_fee', 'discount_amount', 'tracking_number', 'tracking_url',
                'internal_notes', 'cancellation_reason', 'cancelled_at', 'delivered_at'
            ]);
        });
    }
};
