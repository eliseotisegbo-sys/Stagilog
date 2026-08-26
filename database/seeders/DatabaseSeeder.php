<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ecole;
use App\Models\Dossier;
use App\Models\Etudiant;
use App\Models\Cycle;
use App\Models\Filiere;
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
        // 1. Exécuter les seeders de base
        $this->call([
            CycleSeeder::class,
            FiliereSeeder::class,
            AdminSeeder::class,
        ]);

        $cycleLicence = Cycle::where('nom_cycle', 'Licence')->first();
        $cycleMaster = Cycle::where('nom_cycle', 'Master')->first();
        $cycleIng = Cycle::where('nom_cycle', 'Ingénieur')->first();

        $filiereInfo = Filiere::where('nom_filiere', 'Informatique')->first();
        $filiereTelecom = Filiere::where('nom_filiere', 'Télécommunications')->first();
        $filiereGC = Filiere::where('nom_filiere', 'Génie Civil')->first();
        $filiereElec = Filiere::where('nom_filiere', 'Électricité')->first();
        
        // 2. Créer des écoles partenaires
        $ecole1 = Ecole::create([
            'nom_ecole' => 'Université Cheikh Anta Diop (UCAD)',
            'adresse_ecole' => 'Avenue Cheikh Anta Diop, Dakar, Sénégal',
            'num_ecole' => '+221 33 824 69 81',
            'mail' => 'contact@ucad.edu.sn',
            'email' => 'admin@ucad.edu.sn',
            'telephone' => '+221 77 123 45 67',
        ]);

        $ecole2 = Ecole::create([
            'nom_ecole' => 'Université Gaston Berger (UGB)',
            'adresse_ecole' => 'Route de Ngallèle, Saint-Louis, Sénégal',
            'num_ecole' => '+221 33 961 19 06',
            'mail' => 'info@ugb.edu.sn',
            'email' => 'admin@ugb.edu.sn',
            'telephone' => '+221 78 234 56 78',
        ]);

        $ecole3 = Ecole::create([
            'nom_ecole' => 'École Supérieure Polytechnique (ESP)',
            'adresse_ecole' => 'BP 5085, Dakar-Fann, Sénégal',
            'num_ecole' => '+221 33 824 79 55',
            'mail' => 'esp@ucad.edu.sn',
            'email' => 'admin@esp.edu.sn',
            'telephone' => '+221 76 345 67 89',
        ]);

        // 3. Créer des utilisateurs associés aux écoles avec le rôle 'ecole'
        User::create([
            'name' => 'Direction des Stages - UCAD',
            'email' => 'admin@ucad.edu.sn',
            'password' => Hash::make('password123'),
            'role' => 'ecole',
            'id_ecole' => $ecole1->id_ecole,
            'first_login' => false,
        ]);

        User::create([
            'name' => 'Service Insertion - UGB',
            'email' => 'admin@ugb.edu.sn',
            'password' => Hash::make('password123'),
            'role' => 'ecole',
            'id_ecole' => $ecole2->id_ecole,
            'first_login' => false,
        ]);

        User::create([
            'name' => 'Coordination Pédagogique - ESP',
            'email' => 'admin@esp.edu.sn',
            'password' => Hash::make('password123'),
            'role' => 'ecole',
            'id_ecole' => $ecole3->id_ecole,
            'first_login' => false,
        ]);

        // 4. Créer des dossiers de stage avec différents statuts
        // Dossier 1: UCAD - Validé
        $dossier1 = Dossier::create([
            'annee_academique' => '2025-2026',
            'filiere' => 'Génie Informatique',
            'id_filiere' => $filiereInfo ? $filiereInfo->id_filiere : null,
            'id_cycle' => $cycleIng ? $cycleIng->id_cycle : null,
            'type_stage' => 'Stage de fin d\'études',
            'niveau_etude' => 'Master 2 / BAC+5',
            'note_demande' => 'demande_stage_ucad_2025.pdf',
            'datedebut' => '2026-02-01',
            'datefin' => '2026-07-31',
            'id_ecole' => $ecole1->id_ecole,
            'statut' => 'valide',
            'statut_brouillon' => 'soumis',
        ]);

        // Dossier 2: UGB - En attente
        $dossier2 = Dossier::create([
            'annee_academique' => '2025-2026',
            'filiere' => 'Télécommunications & Réseaux',
            'id_filiere' => $filiereTelecom ? $filiereTelecom->id_filiere : null,
            'id_cycle' => $cycleMaster ? $cycleMaster->id_cycle : null,
            'type_stage' => 'Stage d\'immersion professionnelle',
            'niveau_etude' => 'Master 1',
            'note_demande' => 'demande_stage_ugb_2025.pdf',
            'datedebut' => '2026-03-01',
            'datefin' => '2026-06-30',
            'id_ecole' => $ecole2->id_ecole,
            'statut' => 'en_attente',
            'statut_brouillon' => 'soumis',
        ]);

        // Dossier 3: ESP - En attente
        $dossier3 = Dossier::create([
            'annee_academique' => '2025-2026',
            'filiere' => 'Génie Logiciel & IA',
            'id_filiere' => $filiereInfo ? $filiereInfo->id_filiere : null,
            'id_cycle' => $cycleLicence ? $cycleLicence->id_cycle : null,
            'type_stage' => 'Stage ouvrier / découverte',
            'niveau_etude' => 'Licence 3',
            'note_demande' => 'demande_stage_esp_2025.pdf',
            'datedebut' => '2026-04-15',
            'datefin' => '2026-08-15',
            'id_ecole' => $ecole3->id_ecole,
            'statut' => 'en_attente',
            'statut_brouillon' => 'soumis',
        ]);

        // Dossier 4: UCAD - Brouillon
        $dossier4 = Dossier::create([
            'annee_academique' => '2025-2026',
            'filiere' => 'Énergie et Électromécanique',
            'id_filiere' => $filiereElec ? $filiereElec->id_filiere : null,
            'id_cycle' => $cycleIng ? $cycleIng->id_cycle : null,
            'type_stage' => 'Stage de perfectionnement',
            'niveau_etude' => 'Ingénieur 2ème année',
            'note_demande' => null,
            'datedebut' => '2026-05-01',
            'datefin' => '2026-09-30',
            'id_ecole' => $ecole1->id_ecole,
            'statut' => 'en_attente',
            'statut_brouillon' => 'brouillon',
        ]);

        // 5. Créer des étudiants avec CV, contacts et rapports
        // Étudiants Dossier 1 (UCAD - Validé)
        Etudiant::create([
            'nom_etudiant' => 'Diop',
            'prenom_etudiant' => 'Amadou Moustapha',
            'email_etu' => 'amadou.diop@ucad.edu.sn',
            'date_naissance' => '2001-05-14',
            'niveau_etude' => 'Master 2',
            'cv' => 'cv_diop_amadou.pdf',
            'contrat' => 'contrat_diop_amadou.pdf',
            'rapport' => 'rapport_stage_diop_amadou.pdf',
            'pv_stage' => 'pv_stage_diop_amadou.pdf',
            'type_rapport' => 'rapport_etudiant',
            'id_dossier' => $dossier1->id_dossier,
        ]);

        Etudiant::create([
            'nom_etudiant' => 'Ndiaye',
            'prenom_etudiant' => 'Fatou Binetou',
            'email_etu' => 'fatou.ndiaye@ucad.edu.sn',
            'date_naissance' => '2002-09-22',
            'niveau_etude' => 'Master 2',
            'cv' => 'cv_ndiaye_fatou.pdf',
            'contrat' => 'contrat_ndiaye_fatou.pdf',
            'rapport' => 'rapport_ndiaye_fatou.pdf',
            'pv_stage' => null,
            'type_rapport' => 'rapport_etudiant',
            'id_dossier' => $dossier1->id_dossier,
        ]);

        // Étudiants Dossier 2 (UGB - En attente)
        Etudiant::create([
            'nom_etudiant' => 'Sow',
            'prenom_etudiant' => 'Moussa',
            'email_etu' => 'moussa.sow@ugb.edu.sn',
            'date_naissance' => '2002-01-10',
            'niveau_etude' => 'Master 1',
            'cv' => 'cv_sow_moussa.pdf',
            'contrat' => null,
            'rapport' => null,
            'id_dossier' => $dossier2->id_dossier,
        ]);

        Etudiant::create([
            'nom_etudiant' => 'Ba',
            'prenom_etudiant' => 'Aïssatou',
            'email_etu' => 'aissatou.ba@ugb.edu.sn',
            'date_naissance' => '2003-03-30',
            'niveau_etude' => 'Master 1',
            'cv' => 'cv_ba_aissatou.pdf',
            'contrat' => null,
            'rapport' => null,
            'id_dossier' => $dossier2->id_dossier,
        ]);

        // Étudiants Dossier 3 (ESP)
        Etudiant::create([
            'nom_etudiant' => 'Fall',
            'prenom_etudiant' => 'Ibrahima Khalil',
            'email_etu' => 'ibrahima.fall@esp.edu.sn',
            'date_naissance' => '2003-11-18',
            'niveau_etude' => 'Licence 3',
            'cv' => 'cv_fall_ibrahima.pdf',
            'contrat' => null,
            'rapport' => null,
            'id_dossier' => $dossier3->id_dossier,
        ]);

        Etudiant::create([
            'nom_etudiant' => 'Sarr',
            'prenom_etudiant' => 'Mariama',
            'email_etu' => 'mariama.sarr@esp.edu.sn',
            'date_naissance' => '2004-02-05',
            'niveau_etude' => 'Licence 3',
            'cv' => 'cv_sarr_mariama.pdf',
            'contrat' => null,
            'rapport' => null,
            'id_dossier' => $dossier3->id_dossier,
        ]);
    }
}
