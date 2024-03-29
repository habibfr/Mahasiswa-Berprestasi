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
  // private function get_kriteria()
  // {
  //   return Kriteria::where('periode', date('Y'))->get();
  // }
  public function index()
  {
    $kriterias = Kriteria::where('periode', date('Y'))->get();
    $total_bobot = $kriterias->sum('bobot');

    $hasil = [];

    $mahasiswas = Mahasiswa::orderBy('hasils.peringkat', 'asc')
      ->join('hasils', 'hasils.mahasiswa_id', '=', 'mahasiswas.id')
      ->where('hasils.status', 'aktif')
      ->whereBetween('angkatan', [intval(date('Y')) - 3, intval(date('Y')) - 1])
      ->get();

    foreach ($mahasiswas as $mahasiswa) {
      $mahasiswaId = $mahasiswa->id;

      // Tambahkan data mahasiswa
      if (!isset($hasil[$mahasiswaId])) {
        $hasil[$mahasiswaId] = (object) [
          'id' => $mahasiswaId,
          'nim' => $mahasiswa->nim,
          'nama' => $mahasiswa->nama,
          'status' => $mahasiswa->status,
          'jurusan' => $mahasiswa->jurusan,
          // 'nilai' => (object)[],
          'normalisasi' => (object) [],
          'poin' => $mahasiswa->poin ?? 0,
        ];
      }
      // ================================================

      // Tambahkan data nilais
      // =================================================================
      // $kriterias = $this->get_kriteria();
      foreach ($kriterias as $kriteria) {
        $hasil[$mahasiswaId]->normalisasi->{$kriteria->nama_kriteria} = Normalisasi::where('mahasiswa_id', $mahasiswaId)
          ->where('kriteria_id', $kriteria->id)
          ->value('nilai');
      }
      // =================================
    }

    return view('content.peringkat.index', [
      'judul' => 'Peringkat',
      'kriterias' => $kriterias,
      'matrix' => $hasil,
      'total_bobot' => $total_bobot,
      'data_sebelumnya' => true,
    ]);
  }

  public function normalization_per_kriteria(Kriteria $kriteria)
  {
    // panggil nilai dengan kondisi mahasiswa aktif dan kriteria tertentu
    $nilais = Normalisasi::join('kriterias', 'kriterias.id', '=', 'normalisasis.kriteria_id')
      ->join('mahasiswas', 'mahasiswas.id', '=', 'normalisasis.mahasiswa_id')
      ->where('kriterias.nama_kriteria', '=', $kriteria->nama_kriteria)
      ->whereBetween('mahasiswas.angkatan', [intval(date('Y')) - 3, intval(date('Y')) - 1])
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
        $ratio = $minnilais / $nilai->nilai;
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

      // Cek apakah kriteria tahun ini telah ada
      !isset($kriterias->get()[0]->id) ? throw new \Exception('Tidak ada kriteria yang tersedia!') : $kriterias;

      // Cek apakah jumlah kriteria telah berjumlah 1
      $sum_of_kriteria = 0;
      foreach ($kriterias->cursor() as $kriteria) {
        $sum_of_kriteria += $kriteria->bobot;
      }
      // Konversi nilai ke string menggunakan bcmath
      $sum_of_kriteria_str = strval($sum_of_kriteria);
      // $sum_of_kriteria_str < 1 || $sum_of_kriteria > 1
      $sum_of_kriteria_str != 1
        ? throw new \Exception('Jumlah bobot kriteria harus 1!')
        : $kriterias;

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

      $mahasiswas = Mahasiswa::where('status', 'aktif')->whereBetween('angkatan', [
        intval(date('Y')) - 3,
        intval(date('Y')) - 1,
      ]);

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

        $totalScoresByMahasiswa[$mahasiswa->id] = (object) ['poin' => $totalScore * 100];
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
          $totalScoresByMahasiswa[$mahasiswaId]->normalisasi = (object) [];
        }

        foreach ($kriterias->get() as $kriteria) {
          $totalScoresByMahasiswa[$mahasiswaId]->normalisasi->{$kriteria->nama_kriteria} = Normalisasi::where('mahasiswa_id', $mahasiswaId)
            ->where('kriteria_id', $kriteria->id)
            ->value('nilai');
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
