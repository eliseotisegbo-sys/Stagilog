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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'photo_profil')) {
                $table->string('photo_profil', 255)->nullable()->after('email');
            }
        });

        Schema::table('etudiants', function (Blueprint $table) {
            if (!Schema::hasColumn('etudiants', 'photo_profil')) {
                $table->string('photo_profil', 255)->nullable()->after('email_etu');
            }
        });

        Schema::table('dossiers', function (Blueprint $table) {
            if (!Schema::hasColumn('dossiers', 'valide_par')) {
                $table->string('valide_par', 255)->nullable()->after('statut');
            }
            if (!Schema::hasColumn('dossiers', 'refuse_par')) {
                $table->string('refuse_par', 255)->nullable()->after('valide_par');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'photo_profil')) {
                $table->dropColumn('photo_profil');
            }
        });

        Schema::table('etudiants', function (Blueprint $table) {
            if (Schema::hasColumn('etudiants', 'photo_profil')) {
                $table->dropColumn('photo_profil');
            }
        });

        Schema::table('dossiers', function (Blueprint $table) {
            if (Schema::hasColumn('dossiers', 'valide_par')) {
                $table->dropColumn('valide_par');
            }
            if (Schema::hasColumn('dossiers', 'refuse_par')) {
                $table->dropColumn('refuse_par');
            }
        });
    }
};
