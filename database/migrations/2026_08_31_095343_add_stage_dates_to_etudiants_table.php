<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute les dates de stage individuelles par étudiant.
     */
    public function up(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->date('datedebut_stage')->nullable()->after('cv');
            $table->date('datefin_stage')->nullable()->after('datedebut_stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->dropColumn(['datedebut_stage', 'datefin_stage']);
        });
    }
};
