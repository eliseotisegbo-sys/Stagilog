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
        // 1. Mise à jour de la colonne statut sur dossiers pour accepter 'brouillon'
        Schema::table('dossiers', function (Blueprint $table) {
            $table->string('statut', 50)->default('brouillon')->change();
            $table->string('sigle', 50)->nullable()->after('filiere');
        });

        // 2. Création de la table notifications pour les échanges d'actions entre espaces
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_ecole')->nullable();
            $table->enum('target_role', ['admin', 'ecole', 'all'])->default('all');
            $table->string('titre', 255);
            $table->text('message');
            $table->string('type', 50)->default('info');
            $table->string('lien', 255)->nullable();
            $table->boolean('lu')->default(false);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_ecole')->references('id_ecole')->on('ecoles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_notifications');

        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn('sigle');
        });
    }
};
