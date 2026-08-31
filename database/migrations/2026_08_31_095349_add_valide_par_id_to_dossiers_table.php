<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute l'ID de l'administrateur ayant validé le dossier (index interne, invisible des écoles).
     */
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            // ID de l'admin valideur — sans FK contrainte pour éviter les problèmes de suppression
            $table->unsignedBigInteger('valide_par_id')->nullable()->after('valide_par');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn('valide_par_id');
        });
    }
};
