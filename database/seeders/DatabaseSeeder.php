<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ecoles;
use App\Models\dossier;
use App\Models\etudiants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer des écoles
        $ecole1 = ecoles::create([
            'nom_ecole' => 'Université Cheikh Anta Diop',
            'adresse_ecole' => 'Avenue Cheikh Anta Diop, Dakar, Sénégal',
            'num_ecole' => '+221 33 824 69 81',
            'mail' => 'contact@ucad.edu.sn',
        ]);

        $ecole2 = ecoles::create([
            'nom_ecole' => 'Université Gaston Berger',
            'adresse_ecole' => 'Route de Ngallèle, Saint-Louis, Sénégal',
            'num_ecole' => '+221 33 961 19 06',
            'mail' => 'info@ugb.edu.sn',
        ]);

        $ecole3 = ecoles::create([
            'nom_ecole' => 'ESP Dakar',
            'adresse_ecole' => 'BP 5085, Dakar-Fann, Sénégal',
            'num_ecole' => '+221 33 824 79 55',
            'mail' => 'esp@ucad.edu.sn',
        ]);

        // Créer des utilisateurs liés aux écoles
        User::create([
            'name' => 'Admin UCAD',
            'email' => 'admin@ucad.edu.sn',
            'password' => Hash::make('password123'),
            'id_ecole' => $ecole1->id_ecole,
        ]);

        User::create([
            'name' => 'Admin UGB',
            'email' => 'admin@ugb.edu.sn',
            'password' => Hash::make('password123'),
            'id_ecole' => $ecole2->id_ecole,
        ]);

        User::create([
            'name' => 'Admin ESP',
            'email' => 'admin@esp.edu.sn',
            'password' => Hash::make('password123'),
            'id_ecole' => $ecole3->id_ecole,
        ]);

        // Créer des dossiers liés aux écoles
        $dossier1 = dossier::create([
            'annee_academique' => '2025-2026',
            'filiere' => 'Génie Informatique',
            'lettredemande' => 'demande_stage_2025.pdf',
            'datedebut' => '2026-01-15',
            'datefin' => '2026-06-30',
            'id_ecole' => $ecole1->id_ecole,
        ]);

        $dossier2 = dossier::create([
            'annee_academique' => '2025-2026',
            'filiere' => 'Réseaux et Télécommunications',
            'lettredemande' => 'demande_stage_rt_2025.pdf',
            'datedebut' => '2026-02-01',
            'datefin' => '2026-07-31',
            'id_ecole' => $ecole2->id_ecole,
        ]);

        $dossier3 = dossier::create([
            'annee_academique' => '2025-2026',
            'filiere' => 'Génie Logiciel',
            'lettredemande' => 'demande_stage_gl_2025.pdf',
            'datedebut' => '2026-01-20',
            'datefin' => '2026-06-20',
            'id_ecole' => $ecole3->id_ecole,
        ]);

        // Créer des étudiants liés aux dossiers
        // Étudiants pour le dossier 1 (UCAD)
        etudiants::create([
            'nom_etudiant' => 'Diop',
            'prenom_etudiant' => 'Amadou',
            'email_etu' => 'amadou.diop@ucad.edu.sn',
            'cv' => 'cv_diop_amadou.pdf',
            'rapport' => null,
            'id_dossier' => $dossier1->id_dossier,
        ]);

        etudiants::create([
            'nom_etudiant' => 'Ndiaye',
            'prenom_etudiant' => 'Fatou',
            'email_etu' => 'fatou.ndiaye@ucad.edu.sn',
            'cv' => 'cv_ndiaye_fatou.pdf',
            'rapport' => 'rapport_ndiaye_fatou.pdf',
            'id_dossier' => $dossier1->id_dossier,
        ]);

        // Étudiants pour le dossier 2 (UGB)
        etudiants::create([
            'nom_etudiant' => 'Sow',
            'prenom_etudiant' => 'Moussa',
            'email_etu' => 'moussa.sow@ugb.edu.sn',
            'cv' => 'cv_sow_moussa.pdf',
            'rapport' => null,
            'id_dossier' => $dossier2->id_dossier,
        ]);

        etudiants::create([
            'nom_etudiant' => 'Ba',
            'prenom_etudiant' => 'Aissatou',
            'email_etu' => 'aissatou.ba@ugb.edu.sn',
            'cv' => 'cv_ba_aissatou.pdf',
            'rapport' => null,
            'id_dossier' => $dossier2->id_dossier,
        ]);

        // Étudiants pour le dossier 3 (ESP)
        etudiants::create([
            'nom_etudiant' => 'Fall',
            'prenom_etudiant' => 'Ibrahima',
            'email_etu' => 'ibrahima.fall@esp.edu.sn',
            'cv' => 'cv_fall_ibrahima.pdf',
            'rapport' => 'rapport_fall_ibrahima.pdf',
            'id_dossier' => $dossier3->id_dossier,
        ]);

        etudiants::create([
            'nom_etudiant' => 'Sarr',
            'prenom_etudiant' => 'Mariama',
            'email_etu' => 'mariama.sarr@esp.edu.sn',
            'cv' => 'cv_sarr_mariama.pdf',
            'rapport' => null,
            'id_dossier' => $dossier3->id_dossier,
        ]);

        etudiants::create([
            'nom_etudiant' => 'Cisse',
            'prenom_etudiant' => 'Ousmane',
            'email_etu' => 'ousmane.cisse@esp.edu.sn',
            'cv' => 'cv_cisse_ousmane.pdf',
            'rapport' => 'rapport_cisse_ousmane.pdf',
            'id_dossier' => $dossier3->id_dossier,
        ]);
    }
}
