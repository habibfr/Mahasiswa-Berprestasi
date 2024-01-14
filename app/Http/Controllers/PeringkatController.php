<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Normalisasi;
use Illuminate\Http\Request;

class PeringkatController extends Controller
{
  private $totalscoresmahasiswa;
  private $kriterias;
  public function index()
  {
    $kriterias = Kriteria::where('periode', date('Y'))->get();

    $hasil = [];

    $mahasiswas = Mahasiswa::leftJoin('normalisasis', 'normalisasis.mahasiswa_id', '=', 'mahasiswas.id')
      ->leftJoin('hasils', 'hasils.mahasiswa_id', '=', 'mahasiswas.id')
      ->leftJoin('kriterias', 'kriterias.id', '=', 'normalisasis.kriteria_id')
      ->select(
        'mahasiswas.id as mahasiswa_id',
        'mahasiswas.nim',
        'mahasiswas.nama',
        'normalisasis.nilai',
        'kriterias.nama_kriteria',
        'hasils.poin'
      )
      ->where('hasils.status', 'aktif')
      ->orderBy('hasils.peringkat', 'asc')
      ->get();

    foreach ($mahasiswas as $mahasiswa) {
      $mahasiswaId = $mahasiswa->mahasiswa_id;

      if (!isset($hasil[$mahasiswaId])) {
        $hasil[$mahasiswaId] = (object) [
          'id' => $mahasiswaId,
          'nim' => $mahasiswa->nim,
          'nama' => $mahasiswa->nama,
        ];
      }

      if (!empty($mahasiswa->nama_kriteria)) {
        $hasil[$mahasiswaId]->{$mahasiswa->nama_kriteria} = $mahasiswa->nilai;
      }

      $hasil[$mahasiswaId]->poin = $mahasiswa->poin;
    }

    return view('content.peringkat.index', [
      'judul' => 'Peringkat',
      'kriterias' => $kriterias,
      'matrix' => $hasil,
      'data_sebelumnya' => true,
    ]);
  }

  public function normalization_per_kriteria(Kriteria $kriteria)
  {
    // panggil nilai dengan kondisi mahasiswa aktif dan kriteria tertentu
    $nilais = Normalisasi::join('kriterias', 'kriterias.id', '=', 'normalisasis.kriteria_id')
      ->join('mahasiswas', 'mahasiswas.id', '=', 'normalisasis.mahasiswa_id')
      ->where('kriterias.nama_kriteria', '=', $kriteria->nama_kriteria)
      ->where('mahasiswas.status', 'aktif');

    $array_of_normalized = [];

    // logic normalisasi
    // jika tipe kriteria adalah benefit
    if (strcasecmp($kriteria->atribut, 'Benefit') == 0) {
      $maxnilais = $nilais->max('nilai');

      foreach ($nilais->cursor() as $nilai) {
        $ratio = $nilai->nilai / $maxnilais;
        array_push(
          $array_of_normalized,
          (object) [
            'id_mahasiswa' => $nilai->mahasiswa_id,
            'ratio' => $ratio,
          ]
        );
      }
    // jika tipe kriteria adalah cost
    } elseif (strcasecmp($kriteria->atribut, 'Cost') == 0) {
      $minnilais = $nilais->min('nilai');

      foreach ($nilais->cursor() as $nilai) {
        $ratio = $minnilais / $nilai;
        array_push(
          $array_of_normalized,
          (object) [
            'id_mahasiswa' => $nilai->mahasiswa_id,
            'ratio' => $ratio,
          ]
        );
      }
    }

    return $array_of_normalized;
  }

  private function get_result_alternative($jumlah_sorting)
  {
    try {
      //code...
      $kriterias = Kriteria::where('periode', date('Y'));
      // $kriterias = Kriteria::where('periode', '2024');
      $this->kriterias = $kriterias->get();

      !isset($kriterias->get()[0]->id) ? throw new \Exception("Tidak ada kriteria yang tersedia!") : $kriterias;

      $sum_of_kriteria = 0;
      foreach ($kriterias->cursor() as $kriteria) {
        $sum_of_kriteria += $kriteria->bobot;
      }
      $sum_of_kriteria < 1 || $sum_of_kriteria > 1 ? throw new \Exception("Jumlah bobot kriteria harus 1!") : $kriterias;

      $normalized_matrixes = [];

      foreach ($kriterias->cursor() as $kriteria) {
        $nama_kriteria = $kriteria->nama_kriteria;
        array_push(
          $normalized_matrixes,
          (object) [
            'id' => $kriteria->id,
            'nama_kriteria' => $nama_kriteria,
            'bobot' => $kriteria->bobot,
            'ratios' => $this->normalization_per_kriteria($kriteria),
          ]
        );
      }

      $mahasiswas = Mahasiswa::where('status', 'aktif');

      $collections_of_calc = (object) [];

      foreach ($normalized_matrixes as $ratio_and_kriteria) {
        $array_of_ratio = (object) [];
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

        $totalScoresByMahasiswa[$mahasiswa->id] = (object) ['poin' => $totalScore];
      }

      arsort($totalScoresByMahasiswa);

      $totalScoresByMahasiswa = array_slice($totalScoresByMahasiswa, 0, $jumlah_sorting, true);

      $mahasiswas = $mahasiswas
        ->join('normalisasis', 'normalisasis.mahasiswa_id', '=', 'mahasiswas.id')
        ->join('kriterias', 'kriterias.id', '=', 'normalisasis.kriteria_id')
        ->whereIn('mahasiswas.id', array_keys($totalScoresByMahasiswa))
        ->get();

      foreach ($mahasiswas as $mahasiswa) {
        $mahasiswaId = $mahasiswa->mahasiswa_id;

        if (isset($totalScoresByMahasiswa[$mahasiswaId])) {
          $totalScoresByMahasiswa[$mahasiswaId]->id = $mahasiswa->mahasiswa_id;
          $totalScoresByMahasiswa[$mahasiswaId]->nim = $mahasiswa->nim;
          $totalScoresByMahasiswa[$mahasiswaId]->nama = $mahasiswa->nama;
          foreach ($kriterias->get() as $kriteria) {
            if ($kriteria->id == $mahasiswa->kriteria_id) {
              $totalScoresByMahasiswa[$mahasiswaId]->{$kriteria->nama_kriteria} = $mahasiswa->nilai;
            }
          }
        }
      }

      $this->totalscoresmahasiswa = $totalScoresByMahasiswa;
    } catch (\Throwable $th) {
      // throw $th;
      // dd($th->getMessage());
      return back()->with('error', $th->getMessage());
    }
  }
  public function result_alternative(Request $request)
  {
    $this->get_result_alternative($request->jumlah_sorting ?? 10);

    return view('content.peringkat.index', [
      'judul' => 'Peringkat',
      'matrix' => $this->totalscoresmahasiswa,
      'kriterias' => $this->kriterias,
      'jumlah_sorting' => $request->jumlah_sorting,
    ]);
  }

  public function publish(Request $request)
  {
    $this->get_result_alternative($request->jumlah_sorting ?? 10);

    Hasil::where('status', 'aktif')->update(['status' => 'tidak aktif']);

    $array_of_hasil = [];
    $peringkat = 1;

    array_map(function ($scoresmahasiswa) use (&$array_of_hasil, &$peringkat) {
      array_push($array_of_hasil, [
        'mahasiswa_id' => $scoresmahasiswa->id,
        'peringkat' => $peringkat,
        'poin' => $scoresmahasiswa->poin,
        'periode' => date('Y'),
        'status' => 'aktif',
        'created_at' => now(),
        'updated_at' => now(),
      ]);
      $peringkat++;
    }, $this->totalscoresmahasiswa);

    Hasil::insert($array_of_hasil);

    return back()->with('published', 'Berhasil dipublish');
  }
}
