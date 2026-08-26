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
            if (!Schema::hasColumn('dossiers', 'motif_refus')) {
                $table->text('motif_refus')->nullable()->after('statut');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (Schema::hasColumn('dossiers', 'motif_refus')) {
                $table->dropColumn('motif_refus');
            }
        });
    }
};
