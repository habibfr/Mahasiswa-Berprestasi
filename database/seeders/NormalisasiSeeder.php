<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Normalisasi;
use App\Models\Subkriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NormalisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Mahasiswa::where('status', 'aktif')->cursor() as $mahasiswa) {
            
            foreach (Kriteria::cursor() as $kriteria) {
                $sum_of_nilai = 0;

                foreach (Subkriteria::where('kriteria_id', $kriteria->id)
                    ->cursor() as $subkriteria) {
                    $sum_of_nilai += (Nilai::where('subkriteria_id', $subkriteria->id)
                        ->where('mahasiswa_id', $mahasiswa->id)
                        ->value('nilai')*$subkriteria->bobot_normalisasi);
                }

                $data = [
                    'mahasiswa_id' => $mahasiswa->id,
                    'kriteria_id' => $kriteria->id,
                    'nilai' => $sum_of_nilai
                ];

                Normalisasi::create($data);
            }
        }
    }
}
