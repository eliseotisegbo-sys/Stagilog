<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ecoles', function (Blueprint $table) {
            $table->string('sigle', 50)->nullable()->after('nom_ecole');
            $table->string('logo', 255)->nullable()->after('adresse_ecole');
        });

        Schema::table('dossiers', function (Blueprint $table) {
            $table->string('code_dossier', 100)->nullable()->after('id_dossier');
        });

        // Mettre à jour les sigles par défaut pour les écoles existantes si besoin
        $ecoles = DB::table('ecoles')->get();
        foreach ($ecoles as $ecole) {
            $sigle = 'ECOLE';
            if (stripos($ecole->nom_ecole, 'Cheikh Anta Diop') !== false || stripos($ecole->nom_ecole, 'UCAD') !== false) {
                $sigle = 'UCAD';
            } elseif (stripos($ecole->nom_ecole, 'Polytechnique') !== false || stripos($ecole->nom_ecole, 'ESP') !== false) {
                $sigle = 'ESP';
            } else {
                // Générer un sigle basé sur les premières lettres
                $words = preg_split("/\s+/", $ecole->nom_ecole);
                $sigle = '';
                foreach ($words as $w) {
                    if (strlen($w) > 2) {
                        $sigle .= strtoupper(substr($w, 0, 1));
                    }
                }
                if (empty($sigle)) $sigle = 'ECOLE';
            }

            DB::table('ecoles')->where('id_ecole', $ecole->id_ecole)->update(['sigle' => $sigle]);
        }

        // Mettre à jour les dossiers existants avec la nomenclature: SIGLE-jjmmaaaahhmm
        $dossiers = DB::table('dossiers')->get();
        foreach ($dossiers as $dossier) {
            $ecole = DB::table('ecoles')->where('id_ecole', $dossier->id_ecole)->first();
            $sigle = $ecole && $ecole->sigle ? $ecole->sigle : 'STAGE';
            $dateObj = $dossier->created_at ? new \DateTime($dossier->created_at) : new \DateTime();
            $code = strtoupper($sigle) . '-' . $dateObj->format('dmYHi');
            DB::table('dossiers')->where('id_dossier', $dossier->id_dossier)->update(['code_dossier' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn('code_dossier');
        });

        Schema::table('ecoles', function (Blueprint $table) {
            $table->dropColumn(['sigle', 'logo']);
        });
    }
};
