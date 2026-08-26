<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filieres = [
            [
                'nom_filiere' => 'Informatique',
                'description' => 'Développement logiciel, réseaux, intelligence artificielle',
                'actif' => true
            ],
            [
                'nom_filiere' => 'Génie Civil',
                'description' => 'Construction, infrastructures, BTP',
                'actif' => true
            ],
            [
                'nom_filiere' => 'Électricité',
                'description' => 'Installations électriques, énergie, automatisme',
                'actif' => true
            ],
            [
                'nom_filiere' => 'Télécommunications',
                'description' => 'Réseaux, systèmes de communication, fibre optique',
                'actif' => true
            ],
            [
                'nom_filiere' => 'Commerce',
                'description' => 'Marketing, vente, gestion commerciale',
                'actif' => true
            ],
            [
                'nom_filiere' => 'Comptabilité',
                'description' => 'Finance, gestion comptable, audit',
                'actif' => true
            ],
        ];

        foreach ($filieres as $filiere) {
            DB::table('filieres')->insert(array_merge($filiere, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
