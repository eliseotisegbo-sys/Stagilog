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
            $table->date('date_naissance')->nullable()->after('prenom_etudiant');
            $table->string('niveau_etude', 100)->nullable()->after('date_naissance');
            $table->string('contrat', 255)->nullable()->after('cv');
            $table->json('autres_documents')->nullable()->after('contrat');
            
            // Pour gérer plusieurs types de rapports
            $table->string('pv_stage', 255)->nullable()->after('rapport');
            $table->enum('type_rapport', ['rapport_etudiant', 'pv_stage', 'autre'])->nullable()->after('pv_stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->dropColumn([
                'date_naissance',
                'niveau_etude',
                'contrat',
                'autres_documents',
                'pv_stage',
                'type_rapport'
            ]);
        });
    }
};
