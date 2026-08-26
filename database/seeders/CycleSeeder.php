<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cycles')->insert([
            [
                'nom_cycle' => 'Licence',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom_cycle' => 'Master',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom_cycle' => 'Ingénieur',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
