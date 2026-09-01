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
        Schema::table('etudiants', function (Blueprint $table) {
            if (!Schema::hasColumn('etudiants', 'statut_etudiant')) {
                $table->string('statut_etudiant')->default('en_attente')->after('datefin_stage');
            }
            if (!Schema::hasColumn('etudiants', 'motif_refus')) {
                $table->text('motif_refus')->nullable()->after('statut_etudiant');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            if (Schema::hasColumn('etudiants', 'motif_refus')) {
                $table->dropColumn('motif_refus');
            }
            if (Schema::hasColumn('etudiants', 'statut_etudiant')) {
                $table->dropColumn('statut_etudiant');
            }
        });
    }
};
