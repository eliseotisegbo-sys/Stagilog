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
        Schema::table('connexions_historique', function (Blueprint $table) {
            if (!Schema::hasColumn('connexions_historique', 'nom')) {
                $table->string('nom')->nullable()->after('email');
            }
            if (!Schema::hasColumn('connexions_historique', 'session_id')) {
                $table->string('session_id')->nullable()->after('appareil');
            }
            if (!Schema::hasColumn('connexions_historique', 'deconnecte_at')) {
                $table->timestamp('deconnecte_at')->nullable()->after('statut');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('connexions_historique', function (Blueprint $table) {
            if (Schema::hasColumn('connexions_historique', 'nom')) {
                $table->dropColumn('nom');
            }
            if (Schema::hasColumn('connexions_historique', 'session_id')) {
                $table->dropColumn('session_id');
            }
            if (Schema::hasColumn('connexions_historique', 'deconnecte_at')) {
                $table->dropColumn('deconnecte_at');
            }
        });
    }
};
