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
        Schema::table('etudiant_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('etudiant_documents', 'statut')) {
                $table->string('statut', 20)->default('publie')->after('taille_fichier');
            }
        });
    }

    public function down(): void
    {
        Schema::table('etudiant_documents', function (Blueprint $table) {
            if (Schema::hasColumn('etudiant_documents', 'statut')) {
                $table->dropColumn('statut');
            }
        });
    }
};
