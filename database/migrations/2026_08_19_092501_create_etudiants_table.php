<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id('id_etudiant');

            $table->string('nom_etudiant');

            $table->string('prenom_etudiant');

            $table->string('cv')->nullable();

            $table->string('rapport')->nullable();

            $table->foreignId('id_dossier')
                ->constrained('dossiers', 'id_dossier')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};