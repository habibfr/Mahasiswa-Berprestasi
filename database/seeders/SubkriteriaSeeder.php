<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubkriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (DB::table('kriterias')->cursor() as $model) {
            $data = [
                'kriteria_id' => $model->id,
            ];
            if (str_replace(' ', '', strtolower($model->nama_kriteria)) != 'jumlahkaryatulis') {
                $data['nama_subkriteria'] = $model->nama_kriteria;
                $data['bobot_normalisasi'] = 1;

                Subkriteria::create($data);
            } else {
                for ($i = 0; $i < 3; $i++) {
                    $data = ['kriteria_id' => $model->id];
                    $data['nama_subkriteria'] = $i == 0 ? 'Lokal' : ($i == 1 ? 'Nasional' : 'Internasional');
                    $data['bobot_normalisasi'] = $i == 0 ? 1 : ($i == 1 ? 2 : 4);

                    Subkriteria::create($data);
                }
            }
        }
    }
}
