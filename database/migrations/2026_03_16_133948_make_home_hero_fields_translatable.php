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
        Schema::table('store_home_heroes', function (Blueprint $table) {
            $table->json('title')->nullable()->change();
            $table->json('subtitle')->nullable()->change();
            $table->json('description')->nullable()->change();
            $table->json('link')->nullable()->change();
            $table->json('button_text')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_home_heroes', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->string('subtitle')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->string('link')->nullable()->change();
            $table->string('button_text')->nullable()->change();
        });
    }
};
