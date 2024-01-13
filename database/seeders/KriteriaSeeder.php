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
        for ($i = 0; $i < 3; $i++) {
            Kriteria::create([
                'nama_kriteria' => 'IPK',
                'atribut' => 'benefit',
                'bobot' => 0.4,
                'periode' => '202' . ($i + 2),
            ]);

            Kriteria::create([
                'nama_kriteria' => 'IELTS',
                'atribut' => 'benefit',
                'bobot' => 0.2,
                'periode' => '202' . ($i + 2),
            ]);

            Kriteria::create([
                'nama_kriteria' => 'SSKM',
                'atribut' => 'benefit',
                'bobot' => 0.2,
                'periode' => '202' . ($i + 2),
            ]);

            Kriteria::create([
                'nama_kriteria' => 'Jumlah Karya Tulis',
                'atribut' => 'benefit',
                'bobot' => 0.2,
                'periode' => '202' . ($i + 2),
            ]);
        }
    }
}
