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
        Schema::table('dossiers', function (Blueprint $table) {
            // Renommer lettredemande en note_demande si existe
            if (Schema::hasColumn('dossiers', 'lettredemande')) {
                $table->renameColumn('lettredemande', 'note_demande');
            }
            
            // Ajouter statut brouillon si n'existe pas
            if (!Schema::hasColumn('dossiers', 'statut_brouillon')) {
                $table->enum('statut_brouillon', ['brouillon', 'soumis'])
                      ->default('brouillon')
                      ->after('statut');
            }
            
            // Ajouter relations cycle et filiere si n'existent pas
            if (!Schema::hasColumn('dossiers', 'id_cycle')) {
                $table->unsignedBigInteger('id_cycle')->nullable()->after('filiere');
                $table->foreign('id_cycle')->references('id_cycle')->on('cycles')->onDelete('set null');
            }
            if (!Schema::hasColumn('dossiers', 'id_filiere')) {
                $table->unsignedBigInteger('id_filiere')->nullable()->after('id_cycle');
                $table->foreign('id_filiere')->references('id_filiere')->on('filieres')->onDelete('set null');
            }
            
            // Ajouter champs supplémentaires si n'existent pas
            if (!Schema::hasColumn('dossiers', 'type_stage')) {
                $table->string('type_stage', 100)->nullable()->after('id_filiere');
            }
            if (!Schema::hasColumn('dossiers', 'niveau_etude')) {
                $table->string('niveau_etude', 100)->nullable()->after('type_stage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropForeign(['id_cycle']);
            $table->dropForeign(['id_filiere']);
            $table->dropColumn(['statut_brouillon', 'id_cycle', 'id_filiere', 'type_stage', 'niveau_etude']);
            $table->renameColumn('note_demande', 'lettredemande');
        });
    }
};
