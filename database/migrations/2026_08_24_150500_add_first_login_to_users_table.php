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
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->default('ecole')->after('email');
            }
            if (!Schema::hasColumn('users', 'first_login')) {
                $table->boolean('first_login')->default(true)->after('password');
            }
            if (!Schema::hasColumn('users', 'first_login_at')) {
                $table->timestamp('first_login_at')->nullable()->after('first_login');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'first_login', 'first_login_at']);
        });
    }
};
