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
    $nilaiData = [];
    foreach (Mahasiswa::where('status', 'aktif')->cursor() as $data) {

      $ipk = Kriteria::where('nama_kriteria', 'IPK')->where('periode', date('Y'))->value('id');
      $sskm = Kriteria::where('nama_kriteria', 'SSKM')->where('periode', date('Y'))->value('id');
      $ielts = Kriteria::where('nama_kriteria', 'IELTS')->where('periode', date('Y'))->value('id');
      $jkt = Kriteria::where('nama_kriteria', 'Karya Tulis')->where('periode', date('Y'))->value('id');

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
      ];

      $data_sskm = [
        'mahasiswa_id' => $data->id,
        'kriteria_id' => $sskm,
        'nilai' => floatval(rand(100, 200)),
      ];

      $data_ielts = [
        'mahasiswa_id' => $data->id,
        'kriteria_id' => $ielts,
        'nilai' => floatval(rand(2, 9)),
      ];

      $data_jkt = [
        'mahasiswa_id' => $data->id,
        'kriteria_id' => $jkt,
        'nilai' => floatval(rand(0, 5)),
      ];

      Nilai::create($data_ipk);
      Nilai::create($data_sskm);
      Nilai::create($data_ielts);
      Nilai::create($data_jkt);
      // array_push($nilaiData, $data_ipk, $data_sskm);
    }

    // Insert data dummy ke dalam tabel 'nilai'
    // Nilai::create($nilaiData);
  }
}
