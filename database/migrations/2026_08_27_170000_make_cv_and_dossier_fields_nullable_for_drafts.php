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
            $table->string('cv', 255)->nullable()->change();
            $table->string('nom_etudiant', 255)->nullable()->change();
            $table->string('prenom_etudiant', 255)->nullable()->change();
            $table->string('email_etu', 255)->nullable()->change();
            $table->string('niveau_etude', 100)->nullable()->change();
        });

        Schema::table('dossiers', function (Blueprint $table) {
            $table->string('annee_academique', 50)->nullable()->change();
            $table->string('filiere', 255)->nullable()->change();
            $table->date('datedebut')->nullable()->change();
            $table->date('datefin')->nullable()->change();
            $table->string('note_demande', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->string('cv', 255)->nullable(false)->change();
        });
    }
};
