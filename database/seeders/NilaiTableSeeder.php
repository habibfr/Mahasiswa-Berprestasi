<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Subkriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NilaiTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */

  private function chanceipk()
  {
    $chance_ipk = rand(0, 10);
    if ($chance_ipk >= 6) {
      return rand(35, 40) / 10;
    } elseif ($chance_ipk >= 2) {
      return rand(25, 34) / 10;
    } elseif ($chance_ipk > 0) {
      return rand(19, 23) / 10;
    } else {
      return rand(0, 18) / 10;
    }
  }
  public function run(): void
  {
    foreach (Mahasiswa::where('status', 'aktif')->cursor() as $mahasiswa) {
      foreach (Kriteria::cursor() as $kriteria) {
        $nama_kriteria = str_replace(' ', '', strtolower($kriteria->nama_kriteria));

        // jika kriteria bukan jumlah karya tulis
        foreach (Subkriteria::where('kriteria_id', $kriteria->id)->cursor() as $subkriteria) {
          if ($nama_kriteria != 'jumlahkaryatulis') {
            $data = [
              'subkriteria_id' => $subkriteria->id, 
              'mahasiswa_id' => $mahasiswa->id
            ];
            $data['nilai'] = ($nama_kriteria == 'ipk') ? $this->chanceipk() : ($nama_kriteria == 'sskm' ? floatval(rand(100, 200)) : floatval(rand(2, 9)));

            // jika kriteria adalah jumlah karya tulis
          } else {
            $nama_subkriteria = str_replace(' ', '', strtolower($subkriteria->nama_subkriteria));
            $data = ['subkriteria_id' => $subkriteria->id];
            $data['mahasiswa_id'] = $mahasiswa->id;

            if ($nama_subkriteria == 'internasional')
              $data['nilai'] = rand(1, 10) <= 1 ? rand(1, 2) : 0;

            if ($nama_subkriteria == 'nasional')
              $data['nilai'] = rand(1, 10) <= 3 ? rand(1, 2) : 0;

            if ($nama_subkriteria == 'lokal')
              $data['nilai'] = rand(1, 10) <= 6 ? rand(1, 3) : 0;
          }

          Nilai::create($data);
        }
      }
    }
  }
}
