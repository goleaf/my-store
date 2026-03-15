<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Store\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'staff', function (Blueprint $table) {
            $table->renameColumn('lastname', 'last_name');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'staff', function (Blueprint $table) {
            $table->renameColumn('last_name', 'lastname');
        });
    }
};
