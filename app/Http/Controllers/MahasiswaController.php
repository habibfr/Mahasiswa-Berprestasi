<?php

namespace App\Http\Controllers;

use stdClass;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\Normalisasi;
use App\Models\Subkriteria;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
  private function get_jurusan()
  {
    return Mahasiswa::distinct()
      ->pluck('jurusan');
  }
  private function get_kriteria()
  {
    return Kriteria::where('periode', date('Y'))->get();
  }
  private function get_subkriterias($kriterias_id = false)
  {
    return $kriterias_id != false ? Subkriteria::where('kriteria_id', $kriterias_id)->get() : Subkriteria::get();
  }
  public function index()
  {
    $jurusan = $this->get_jurusan();
    $kriterias = $this->get_kriteria();
    $min_angkatan = Mahasiswa::whereBetween('angkatan', [intval(date('Y')) - 3, intval(date('Y')) - 1])->min('angkatan');
    $max_angkatan = Mahasiswa::whereBetween('angkatan', [intval(date('Y')) - 3, intval(date('Y')) - 1])->max('angkatan');
    $arrsubkriterias = [];

    // ambil subkriteria
    foreach ($kriterias as $kriteria) {

      $subkriterias = $this->get_subkriterias($kriteria->id);
      foreach ($subkriterias as $subkriteria) {
        array_push($arrsubkriterias, $subkriteria);
      }
    }
    $arrsubkriterias = collect($arrsubkriterias);

    return view('content.mahasiswa.index', [
      'jurusan' => $jurusan,
      'kriterias' => $kriterias,
      'subkriterias' => $arrsubkriterias,
      'min_angkatan' => $min_angkatan,
      'max_angkatan' => $max_angkatan,
      'judul' => 'Mahasiswa',
    ]);
    // $mahasiswas = Mahasiswa::onlyTrashed()->get();
    // dd($mahasiswas);
  }
  public function get_mahasiswas(Request $request)
  {
    if ($request->ajax()) {
      $hasil = [];
      // $hasil = new stdClass;
      // ===================================================
      $mahasiswas = Mahasiswa::orderBy('mahasiswas.nim', 'asc')
        ->whereBetween('angkatan', [intval(date('Y')) - 3, intval(date('Y')) - 1])
        ->get();
      // ->filter(function ($value, $key) {
      //   $year = intval(date('Y')) - intval($value->angkatan);
      //   if ($year > 1 && $year <= 3) {
      //     return $value;
      //   }
      // });

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
            'normalisasi' => (object)[],
          ];
        }
        // ================================================

        // Tambahkan data nilais
        // =================================================================
        $kriterias = $this->get_kriteria();
        foreach ($kriterias as $kriteria) {
          $hasil[$mahasiswaId]
            ->normalisasi
            ->{$kriteria->nama_kriteria}
            = Normalisasi::where('mahasiswa_id', $mahasiswaId)
            ->where('kriteria_id', $kriteria->id)
            ->value('nilai');
        }
        // =================================
      }
      // end loop of mahasiswa

      return DataTables::of(collect($hasil))
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          $action_button = '<div class="inline-block">
                  <span data-id="' . $row->id . '" class="btnEdit btn-outline-warning btn-icon btn" type="button" role="button"
                      data-bs-toggle="modal" data-bs-target="#modalEditMhs">
                      <i class="bx bx-edit-alt"></i>
                  </span>
                  <span data-id="' . $row->id . '" class="text-danger btnHapus btn-outline-danger btn-icon btn" type="button" role="button"
                      data-bs-toggle="modal" data-bs-target="#modalHapusMhs">
                      <i class="bx bx-trash"></i>
                  </span>
            </div>';
          return $action_button;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
  }
  public function filter(Request $request)
  {
    try {
      $jurusan = $request->jurusan;
      $angkatan = $request->angkatan;
      $kriterias = $this->get_kriteria();
      $jurusans = $this->get_jurusan();

      $min_year = Mahasiswa::min('angkatan');
      $max_year = Mahasiswa::max('angkatan');
      $this->validate($request, [
        'jurusan' => 'nullable|array|max:' . count($jurusans),
        'angkatan' => 'nullable|numeric|between:' . $min_year . ',' . $max_year,
      ]);

      $dataJurusan = [];
      foreach ($this->get_jurusan() as $value) {
        array_push($dataJurusan, $value);
      }

      // $persamaan = array_intersect($jurusan, $dataJurusan);

      // Filter Jurusan
      $data = Mahasiswa::query();

      if (!empty($jurusan) && $jurusan[0] != 0) {
        $data->whereIn('jurusan', $jurusan);
      }

      // Filter Angkatan
      if (!is_null($angkatan)) {
        $data->where('angkatan', $angkatan);
      }

      $mahasiswas = $data->orderBy('mahasiswas.nim', 'asc')
        ->whereBetween('angkatan', [intval(date('Y')) - 3, intval(date('Y')) - 1])
        ->get();
      // ->filter(function ($value, $key) {
      //   $year = intval(date('Y')) - intval($value->angkatan);
      //   if ($year > 1 && $year <= 3) {
      //     return $value;
      //   }
      // });

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
            'normalisasi' => (object)[],
          ];
        }
        // ================================================

        // Tambahkan data nilais
        // =================================================================
        $kriterias = $this->get_kriteria();
        foreach ($kriterias as $kriteria) {
          $hasil[$mahasiswaId]
            ->normalisasi
            ->{$kriteria->nama_kriteria}
            = Normalisasi::where('mahasiswa_id', $mahasiswaId)
            ->where('kriteria_id', $kriteria->id)
            ->value('nilai');
        }
        // =================================
      }
      // end loop of mahasiswa

      return DataTables::of(collect($hasil))
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          $action_button = '<div class="inline-block">
                                <span data-id="' . $row->id . '" class="btnEdit btn-outline-warning btn-icon btn" type="button" role="button"
                                    data-bs-toggle="modal" data-bs-target="#modalEditMhs">
                                    <i class="bx bx-edit-alt"></i>
                                </span>
                                <span data-id="' . $row->id . '" class="text-danger btnHapus btn-outline-danger btn-icon btn" type="button" role="button"
                                    data-bs-toggle="modal" data-bs-target="#modalHapusMhs">
                                    <i class="bx bx-trash"></i>
                                </span>
                          </div>';
          return $action_button;
        })
        ->rawColumns(['action'])
        ->make(true);
    } catch (\Throwable $th) {
      return redirect('mahasiswa')->with('error', $th);
      // return response()->json(['error' => $th->getMessage()], 500);
    }
  }

  public function getMahasiswaById($id)
  {
    try {
      $kriterias = $this->get_kriteria();

      $mahasiswas = Mahasiswa::where('mahasiswas.id', $id)->get();

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
            'nilai' => [],
            // 'normalisasi' => (object)[],
          ];
        }
        // ================================================

        // Tambahkan data nilais
        // =================================================================
        $kriterias = $this->get_kriteria();
        foreach ($kriterias as $kriteria) {

          $subkriterias = $this->get_subkriterias($kriteria->id);
          foreach ($subkriterias as $subkriteria) {
            $nilai = Nilai::where('mahasiswa_id', $mahasiswaId)
              ->where('subkriteria_id', $subkriteria->id)
              ->value('nilai');

            isset($nilai) ? $nilai : $nilai = 0;

            if (str_replace(' ', '', strtolower($kriteria->nama_kriteria)) == str_replace(' ', '', strtolower($subkriteria->nama_subkriteria))) {
              $hasil[$mahasiswaId]
                ->nilai[$kriteria->nama_kriteria] = $nilai;
            } else {
              $hasil[$mahasiswaId]
                ->nilai[$subkriteria->nama_subkriteria] = $nilai;
            }
          }
        }
        // =================================
      }

      return response()->json($hasil);
    } catch (\Throwable $th) {
      return redirect('mahasiswa')->with('error', $th);
    }
  }

  public function updateMahasiswa(Request $request, $id)
  {
    try {
      //code...
      $kriterias = $this->get_kriteria();
      $subkriterias = $this->get_subkriterias();

      $request_validate = [];

      foreach ($kriterias as $kriteria) {
        if ($subkriterias->where('kriteria_id', $kriteria->id)->isNotEmpty()) {
          foreach ($subkriterias->where('kriteria_id', $kriteria->id) as $subkriteria) {
            $request_validate[str_replace(' ', '_', strtolower($kriteria->nama_kriteria)) . '_' . $subkriteria->id] = 'nullable|numeric';
          }
        }
      }

      $request->validate($request_validate);

      // Ambil data mahasiswa dari database berdasarkan ID
      $mahasiswa = Mahasiswa::find($id);

      if (!$mahasiswa) {
        return back()->with('error', 'Mahasiswa tidak ada!');
      }

      $datas_nilai = [];
      foreach ($kriterias as $kriteria) {
        if ($subkriterias->where('kriteria_id', $kriteria->id)->isNotEmpty()) {
          $sum_of_nilai = 0;

          foreach ($subkriterias->where('kriteria_id', $kriteria->id) as $subkriteria) {

            $id_input = str_replace(' ', '_', strtolower($subkriteria->nama_subkriteria)) . '_' . $subkriteria->id;

            if (!key_exists($id_input, $request->input())) {
              continue;
            }

            // Cari data nilai dengan id mahasiswa dan id subkrteria
            $nilai = Nilai::where('mahasiswa_id', $id)
              ->whereHas('subkriteria', function ($query) use ($subkriteria) {
                $query->where('nama_subkriteria', $subkriteria->nama_subkriteria);
              })
              ->first();

            // Update nilai jika nilai ada, jika tidak buat baru
            if ($nilai) {
              $nilai->where('subkriteria_id', $subkriteria->id)->update(['nilai' => $request->input($id_input)]);
            } else {
              Nilai::create([
                'mahasiswa_id' => $id,
                'nilai' => $request->input($id_input),
                'subkriteria_id' => $subkriteria->id,
              ]);
            }

            $sum_of_nilai += ($request->input($id_input) * $subkriteria->bobot_normalisasi);
          }

          $normalisasi = Normalisasi::where('kriteria_id', $kriteria->id)
            ->where('mahasiswa_id', $id)
            ->first();

          $normalisasi ?
            Normalisasi::where('kriteria_id', $kriteria->id)
              ->where('mahasiswa_id', $id)
              ->update(['nilai' => $sum_of_nilai])
            :
            Normalisasi::create([
              'mahasiswa_id' => $id,
              'nilai' => $sum_of_nilai,
              'kriteria_id' => $kriteria->id
            ]);
        }
      }

      return redirect()->route('mahasiswa')->with('update_mahasiswa', 'Data mahasiswa berhasil diperbarui');
    } catch (\Throwable $th) {
      //throw $th;
      return redirect('mahasiswa')->with('error', $th->getMessage());
    }
  }

  public function destroy($id)
  {
    // $id->validate([
    //   'id' => 'string|required',
    // ]);
    $mahasiswa = Mahasiswa::find($id);
    $nilai = Nilai::where('mahasiswa_id', $id);
    $normalisasi = Normalisasi::where('mahasiswa_id', $id);

    if ($mahasiswa) {
      $mahasiswa->delete();
      $nilai->delete();
      $normalisasi->delete();

      // You can return a response if needed
      return redirect('mahasiswa')->with('mahasiswa_deleted', 'Mahasiswa deleted successfully');
    } else {
      // Return a response indicating that the Mahasiswa was not found
      return redirect('mahasiswa')->with('error', 'Mahasiswa not found');
    }
  }
}
