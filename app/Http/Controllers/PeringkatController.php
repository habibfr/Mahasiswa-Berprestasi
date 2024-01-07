<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;
use stdClass;

class PeringkatController extends Controller
{
  public function index()
  {
    return view('content.peringkat.index', ['judul' => 'Peringkat']);
  }

  public function normalization_per_kriteria(Kriteria $kriteria)
  {
    // $kriteria->nama_kriteria = 'IPK';
    // $kriteria->atribut = 'Benefit';

    // panggil nilai dengan kondisi mahasiswa aktif dan kriteria tertentu
    $nilais = Nilai::join('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
      ->join('mahasiswas', 'mahasiswas.id', '=', 'nilais.mahasiswa_id')
      ->where('kriterias.nama_kriteria', $kriteria->nama_kriteria)
      ->where('mahasiswas.status', 'aktif');

    $array_of_normalized = [];
    $normalized = new stdClass();

    // logic normalisasi
    if ($kriteria->atribut == 'Benefit') {
      $maxnilais = $nilais->max('nilai');

      foreach ($nilais->cursor() as $nilai) {
        $ratio = $nilai->nilai / $maxnilais;
        $id_mahasiswa = $nilai->mahasiswa_id;
        $normalized->id_mahasiswa = $id_mahasiswa;
        $normalized->ratio = $ratio;
        array_push($array_of_normalized, $normalized);
      }
    } else {
      $minnilais = $nilais->min('nilai');

      foreach ($nilais->cursor() as $nilai) {
        $ratio = $minnilais / $nilai;
        $id_mahasiswa = $nilai->mahasiswa_id;
        $normalized->id_mahasiswa = $id_mahasiswa;
        $normalized->ratio = $ratio;
        array_push($array_of_normalized, $normalized);
      }
    }

    return $array_of_normalized;
    // return view('content.peringkat.index', ['matrix' => $array_of_normalized, 'judul' => 'Peringkat']);
  }

  public function result_alternative(Request $request)
  {
    // $kriterias = Kriteria::where('periode', date('Y'))->get();
    $kriterias = Kriteria::where('periode', '2023');

    $normalized_matrixes = [];
    $normalized_matrix = new stdClass();

    foreach ($kriterias->cursor() as $kriteria) {
      $nama_kriteria = $kriteria->nama_kriteria;
      $array_of_ratio = $this->normalization_per_kriteria($kriteria);
      $normalized_matrix->id = $kriteria->id;
      $normalized_matrix->nama_kriteria = $nama_kriteria;
      $normalized_matrix->bobot = $kriteria->bobot;
      $normalized_matrix->ratios = $array_of_ratio;
      array_push($normalized_matrixes, $normalized_matrix);
    }

    // $mahasiswas = Mahasiswa::where('status', 'aktif');

    for ($i = 0; $i < count($normalized_matrixes); $i++) {
      $array_of_kriteria = $normalized_matrixes[$i];
    }

    return view('content.peringkat.index', ['judul' => 'Peringkat', 'matrix' => $normalized_matrixes]);
  }
}
