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
        Schema::create('etudiant_documents', function (Blueprint $table) {
            $table->id('id_document');
            $table->unsignedBigInteger('id_etudiant');
            $table->string('nom_document', 255);
            $table->string('fichier', 255);
            $table->string('taille_fichier', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_etudiant')
                  ->references('id_etudiant')
                  ->on('etudiants')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiant_documents');
    }
};
