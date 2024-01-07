<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;

class PeringkatController extends Controller
{
  public function index()
  {
    $kriterias = Kriteria::where('periode', '2023')->get();

    return view('content.peringkat.index', ['judul' => 'Peringkat', 'kriterias' => $kriterias]);
  }

  public function normalization_per_kriteria(Kriteria $kriteria)
  {
    // panggil nilai dengan kondisi mahasiswa aktif dan kriteria tertentu
    $nilais = Nilai::join('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
      ->join('mahasiswas', 'mahasiswas.id', '=', 'nilais.mahasiswa_id')
      ->where('kriterias.nama_kriteria', '=', $kriteria->nama_kriteria)
      ->where('mahasiswas.status', 'aktif');

    $array_of_normalized = [];

    // logic normalisasi
    if (strcasecmp($kriteria->atribut, 'Benefit') == 0) {
      $maxnilais = $nilais->max('nilai');

      foreach ($nilais->cursor() as $nilai) {
        $ratio = $nilai->nilai / $maxnilais;
        array_push($array_of_normalized, (object) [
          'id_mahasiswa' => $nilai->mahasiswa_id,
          'ratio' => $ratio,
        ]);
      }
    } else if (strcasecmp($kriteria->atribut, 'Cost') == 0) {
      $minnilais = $nilais->min('nilai');

      foreach ($nilais->cursor() as $nilai) {
        $ratio = $minnilais / $nilai;
        array_push($array_of_normalized, (object) [
          'id_mahasiswa' => $nilai->mahasiswa_id,
          'ratio' => $ratio,
        ]);
      }
    }

    return $array_of_normalized;
  }

  public function result_alternative(Request $request)
  {
    // $kriterias = Kriteria::where('periode', date('Y'))->get();
    $kriterias = Kriteria::where('periode', '2023');

    $normalized_matrixes = [];

    foreach ($kriterias->cursor() as $kriteria) {
      $nama_kriteria = $kriteria->nama_kriteria;
      array_push($normalized_matrixes, (object) [
        'id' => $kriteria->id,
        'nama_kriteria' => $nama_kriteria,
        'bobot' => $kriteria->bobot,
        'ratios' => $this->normalization_per_kriteria($kriteria),
      ]);
    }

    $mahasiswas = Mahasiswa::where('status', 'aktif');

    $collections_of_calc = (object)[];

    foreach ($normalized_matrixes as $ratio_and_kriteria) {
      $array_of_ratio = (object)[];
      foreach ($ratio_and_kriteria->ratios as $ratio) {
        $array_of_ratio->{$ratio->id_mahasiswa} = $ratio->ratio * $ratio_and_kriteria->bobot;
      }
      $collections_of_calc->{$ratio_and_kriteria->nama_kriteria} = $array_of_ratio;
    }

    foreach ($mahasiswas->cursor() as $mahasiswa) {
      $totalScore = 0; // Inisialisasi total nilai untuk setiap mahasiswa

      // Looping through $collections_of_calc untuk setiap kriteria
      foreach ($collections_of_calc as $kriteriaNama => $kriteriaRatios) {
        // Jika ID mahasiswa ada dalam koleksi perhitungan kriteria
        if (property_exists($kriteriaRatios, $mahasiswa->id)) {
          $totalScore += $kriteriaRatios->{$mahasiswa->id}; // Tambahkan nilai kriteria ke total
        }
      }

      $totalScoresByMahasiswa[$mahasiswa->id] = (object)['poin' => $totalScore];
    }

    arsort($totalScoresByMahasiswa);

    $totalScoresByMahasiswa = array_slice($totalScoresByMahasiswa, 0, $request->jumlah_sorting, true);

    $mahasiswas = $mahasiswas->join('nilais', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
      ->join('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
      ->whereIn('mahasiswas.id', array_keys($totalScoresByMahasiswa))
      ->get();

    foreach ($mahasiswas as $mahasiswa) {
      $mahasiswaId = $mahasiswa->mahasiswa_id;
      
      if (isset($totalScoresByMahasiswa[$mahasiswaId])) {
        $totalScoresByMahasiswa[$mahasiswaId]->nim = $mahasiswa->nim;
        $totalScoresByMahasiswa[$mahasiswaId]->nama = $mahasiswa->nama;
        foreach ($kriterias->get() as $kriteria) {
          if ($kriteria->id == $mahasiswa->kriteria_id) {
            $totalScoresByMahasiswa[$mahasiswaId]->{$kriteria->nama_kriteria} = $mahasiswa->nilai;
          }
        }
      }
    }

    return view('content.peringkat.index', ['judul' => 'Peringkat', 'matrix' => $totalScoresByMahasiswa, 'kriterias' => $kriterias->get()]);
  }
}
