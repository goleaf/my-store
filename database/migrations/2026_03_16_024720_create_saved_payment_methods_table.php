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
        Schema::create('saved_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('store_customers')->onDelete('cascade');
            $table->enum('type', ['card', 'paypal', 'payoneer']);
            
            // Card fields (from Stripe)
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_payment_method_id')->nullable();
            $table->char('last_four', 4)->nullable();
            $table->string('brand', 50)->nullable();
            $table->unsignedTinyInteger('expiry_month')->nullable();
            $table->unsignedSmallInteger('expiry_year')->nullable();
            
            // PayPal fields
            $table->string('paypal_email')->nullable();
            
            // Payoneer fields
            $table->string('payoneer_account_id')->nullable();
            
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_payment_methods');
    }
};
