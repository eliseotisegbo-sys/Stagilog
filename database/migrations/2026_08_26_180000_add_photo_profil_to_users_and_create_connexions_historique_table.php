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
        // 1. Ajouter photo_profil à users si pas déjà présent
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'photo_profil')) {
                $table->string('photo_profil')->nullable()->after('email');
            }
        });

        // 2. Créer la table connexions_historique
        if (!Schema::hasTable('connexions_historique')) {
            Schema::create('connexions_historique', function (Blueprint $table) {
                $table->id('id_connexion');
                $table->unsignedBigInteger('id_user')->nullable();
                $table->string('email');
                $table->string('role')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('navigateur')->nullable();
                $table->string('appareil')->nullable();
                $table->string('statut')->default('succes'); // succes, echec, otp_en_attente
                $table->timestamps();

                $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connexions_historique');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'photo_profil')) {
                $table->dropColumn('photo_profil');
            }
        });
    }
};
