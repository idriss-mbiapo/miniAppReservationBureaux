<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BureauSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bureaux')->insert([
            ['nom' => 'Bureau 1', 'emplacement' => 'Étage 1'],
            ['nom' => 'Bureau 2', 'emplacement' => 'Étage 2'],
            ['nom' => 'Bureau 3', 'emplacement' => 'Étage 3'],
        ]);
    }
}
