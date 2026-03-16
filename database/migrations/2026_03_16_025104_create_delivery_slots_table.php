<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->nullable()->constrained('delivery_zones')->onDelete('cascade');
            $table->enum('day_type', ['specific', 'recurring'])->default('recurring');
            $table->date('specific_date')->nullable();
            $table->unsignedTinyInteger('day_of_week')->nullable(); // 0=Sun
            $table->string('label', 100);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedTinyInteger('cutoff_hours')->default(0);
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->unsignedInteger('capacity')->default(50);
            $table->unsignedInteger('booked_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_slots');
    }
};
