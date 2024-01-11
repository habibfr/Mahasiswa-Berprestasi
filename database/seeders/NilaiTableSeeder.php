<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NilaiTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //
    // $nilaiData = [
    //     [
    //         'mahasiswa_id' => 1, // Sesuaikan dengan ID mahasiswa dari seeder MahasiswaSeeder
    //         'IPK' => 3.25,
    //         'SSKM' => 250,
    //         'TOEFL' => 550,
    //         'karya_tulis' => 2,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ],
    //     [
    //         'mahasiswa_id' => 2, // Sesuaikan dengan ID mahasiswa lainnya
    //         'IPK' => 3.50,
    //         'SSKM' => 200,
    //         'TOEFL' => 600,
    //         'karya_tulis' => 1,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ],
    //     [
    //         'mahasiswa_id' => 3, // Sesuaikan dengan ID mahasiswa lainnya
    //         'IPK' => 3.90,
    //         'SSKM' => 150,
    //         'TOEFL' => 500,
    //         'karya_tulis' => 3,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ],
    // ];
    $nilaiData = [];
    // $data = Mahasiswa::where('status', 'aktif')->get();
    // for ($i = 1; $i < $data->count(); $i++) {
    foreach (Mahasiswa::where('status', 'aktif')->cursor() as $data) {
      // if ($i < 10) {
      //     $nim = '2141010000' . $i;
      // } else if ($i < 100) {
      //     $nim = '214101000' . $i;
      // } else {
      //     $nim = '21410100' . $i;
      // }
      $nim = $data->nim;

      $ipk = Kriteria::where('nama_kriteria', 'IPK')->value('id');
      $sskm = Kriteria::where('nama_kriteria', 'SSKM')->value('id');

      $chance_ipk = rand(0, 10);
      if ($chance_ipk >= 6) {
        $nilai_ipk = rand(35, 40) / 10;
      } elseif ($chance_ipk >= 2) {
        $nilai_ipk = rand(25, 34) / 10;
      } elseif ($chance_ipk > 0) {
        $nilai_ipk = rand(19, 23) / 10;
      } else {
        $nilai_ipk = rand(0, 18) / 10;
      }

      $data_ipk = [
        'mahasiswa_id' => $data->id,
        'kriteria_id' => $ipk,
        'nilai' => $nilai_ipk,
        'created_at' => now(),
        'updated_at' => now(),
      ];

      $data_sskm = [
        'mahasiswa_id' => $data->id,
        'kriteria_id' => $sskm,
        'nilai' => floatval(rand(100, 200)),
        'created_at' => now(),
        'updated_at' => now(),
      ];

      array_push($nilaiData, $data_ipk, $data_sskm);
    }

    // Insert data dummy ke dalam tabel 'nilai'
    Nilai::insert($nilaiData);
  }
}
