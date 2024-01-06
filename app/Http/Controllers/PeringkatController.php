<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
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

    $nilais = Nilai::join('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
      ->join('mahasiswas', 'mahasiswas.id', '=', 'nilais.mahasiswa_id')
      ->where('kriterias.nama_kriteria', $kriteria->nama_kriteria)
      ->where('mahasiswas.status', 'aktif');

    // $normalized = array();
    $normalized = new stdClass();

    if ($kriteria->atribut == 'Benefit') {
      $maxnilais = $nilais->max('nilai');

      foreach ($nilais->cursor() as $nilai) {
        $ratio = $nilai->nilai / $maxnilais;
        $id_mahasiswa = $nilai->mahasiswa_id;
        $normalized->$id_mahasiswa = $ratio;
        // array_push($normalized, $nilai);
      }
    } else {
      $minnilais = $nilais->min('nilai');

      foreach ($nilais->cursor() as $nilai) {
        $ratio = $minnilais / $nilai;
        $id_mahasiswa = $nilai->mahasiswa_id;
        $normalized->$id_mahasiswa = $ratio;
        // array_push($normalized, $ratio);
      }
    }

    return $normalized;
    // return view('content.peringkat.index', ['normalized' => $normalized, 'judul' => 'Peringkat']);
  }

  public function result_alternative(Request $request)
  {
    // $kriterias = Kriteria::where('periode', date('Y'))->get();
    $kriterias = Kriteria::where('periode', '2023');

    $normalized_matrix = array();

    foreach ($kriterias->cursor() as $kriteria) {
      array_push($normalized_matrix, $this->normalization_per_kriteria($kriteria));
    }

    return view('content.peringkat.index', ['judul' => 'Peringkat', 'kriterias' => $normalized_matrix]);
  }
}
