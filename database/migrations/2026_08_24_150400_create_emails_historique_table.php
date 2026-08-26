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
        Schema::create('emails_historique', function (Blueprint $table) {
            $table->id('id_email');
            $table->string('destinataire', 255);
            $table->string('sujet', 255);
            $table->text('contenu');
            $table->string('type_email', 50);
            $table->boolean('envoye')->default(false);
            $table->timestamp('date_envoi')->nullable();
            $table->unsignedBigInteger('id_ecole')->nullable();
            $table->timestamps();
            
            $table->foreign('id_ecole')->references('id_ecole')->on('ecoles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails_historique');
    }
};
