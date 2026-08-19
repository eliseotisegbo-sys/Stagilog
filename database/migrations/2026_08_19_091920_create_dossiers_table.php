<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id('id_dossier');

            $table->string('annee_academique', 50);

            $table->string('filiere');

            $table->string('lettredemande', 255)->nullable();

            $table->date('datedebut');

            $table->date('datefin');

            $table->foreignId('id_ecole')
                ->constrained('ecoles', 'id_ecole')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};