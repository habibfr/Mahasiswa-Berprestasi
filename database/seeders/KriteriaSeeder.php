<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Kriteria::create([
            'nama_kriteria' => 'IPK',
            'atribut' => 'benefit',
            'bobot' => 0.4,
            'periode' => '2023'
        ]);

        Kriteria::create([
            'nama_kriteria' => 'TOEFL',
            'atribut' => 'benefit',
            'bobot' => 0.2,
            'periode' => '2023'
        ]);

        Kriteria::create([
            'nama_kriteria' => 'SSKM',
            'atribut' => 'benefit',
            'bobot' => 0.2,
            'periode' => '2023'
        ]);

        Kriteria::create([
            'nama_kriteria' => 'Karya Tulis',
            'atribut' => 'benefit',
            'bobot' => 0.2,
            'periode' => '2023'
        ]);
    }
}
