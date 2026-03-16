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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'store_owner', 'editor', 'admin', 'super_admin'])->default('customer')->after('password');
            $table->string('phone', 20)->nullable()->after('email');
            $table->enum('status', ['active', 'banned', 'unverified'])->default('unverified')->after('role');
            $table->string('locale', 10)->default('en')->after('status');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('avatar', 500)->nullable()->after('last_login_at');
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'status', 'locale', 'last_login_at', 'avatar', 'deleted_at']);
        });
    }
};
