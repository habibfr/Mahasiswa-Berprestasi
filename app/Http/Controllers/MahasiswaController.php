<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Mahasiswa;
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
  public function index()
  {
    $jurusan = $this->get_jurusan();
    $kriterias = $this->get_kriteria();

    $hasil = [];

    $mahasiswas = Mahasiswa::leftJoin('nilais', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
      ->leftJoin('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
      ->select(
        'mahasiswas.id as mahasiswa_id',
        'mahasiswas.nim',
        'mahasiswas.nama',
        'mahasiswas.status',
        'mahasiswas.jurusan',
        'mahasiswas.angkatan',
        'nilais.nilai',
        'kriterias.nama_kriteria'
      )
      ->orderBy('mahasiswas.nim', 'asc')
      ->get()
      ->filter(function ($value, $key) {
        $year = intval(date('Y')) - intval($value->angkatan);
        if ($year > 1 && $year <= 3) {
          return $value;
        }
      });

    foreach ($mahasiswas as $mahasiswa) {
      $mahasiswaId = $mahasiswa->mahasiswa_id;

      if (!isset($hasil[$mahasiswaId])) {
        $hasil[$mahasiswaId] = (object) [
          'id' => $mahasiswaId,
          'nim' => $mahasiswa->nim,
          'nama' => $mahasiswa->nama,
          'status' => $mahasiswa->status,
          'jurusan' => $mahasiswa->jurusan,
        ];
      }

      if (!empty($mahasiswa->nama_kriteria)) {
        $hasil[$mahasiswaId]->{$mahasiswa->nama_kriteria} = $mahasiswa->nilai;
      }

      // $hasil[$mahasiswaId]->poin = $mahasiswa->poin;
    }

    return view('content.mahasiswa.index', [
      'jurusan' => $jurusan,
      'kriterias' => $kriterias,
      'data' => $hasil,
      'judul' => 'Mahasiswa',
    ]);
  }

  function importData(Request $request)
  {
    $this->validate($request, [
      'uploaded_file' => 'required|file|mimes:xls,xlsx',
    ]);
    $the_file = $request->file('uploaded_file');

    try {
      $spreadsheet = IOFactory::load($the_file->getRealPath());
      $sheet = $spreadsheet->getActiveSheet();
      $row_limit = $sheet->getHighestDataRow();
      $column_limit = $sheet->getHighestDataColumn();
      $row_range = range(2, $row_limit);
      // $column_range = range( 'H', $column_limit );
      $startcount = 2;
      // $data = array();

      $jumlahBaris = Mahasiswa::count() + 1;

      foreach ($row_range as $row) {
        $dataMahasiswa[] = [
          // 'CustomerName' =>$sheet->getCell( 'A' . $row )->getValue(),
          'nim' => $sheet->getCell('B' . $row)->getValue(),
          'nama' => $sheet->getCell('C' . $row)->getValue(),
          'angkatan' => $sheet->getCell('D' . $row)->getValue(),
          'status' => $sheet->getCell('E' . $row)->getValue(),
          'jurusan' => $sheet->getCell('F' . $row)->getValue(),
        ];
      }
      Mahasiswa::insert($dataMahasiswa);

      foreach ($row_range as $row) {
        $mahasiswa = Mahasiswa::where('nim', $sheet->getCell('B' . $row)->getValue())->first();
        // dd()
        $dataNilai[] = [
          // 'CustomerName' =>$sheet->getCell( 'A' . $row )->getValue(),
          // 'nim' => $sheet->getCell( 'B' . $row )->getValue(),

          'mahasiswa_id' => $mahasiswa->id,
          'IPK' => $sheet->getCell('F' . $row)->getValue(),
          'SSKM' => $sheet->getCell('G' . $row)->getValue(),
          'TOEFL' => $sheet->getCell('H' . $row)->getValue(),
        ];
      }
      // Insert into Nilai table
      Nilai::insert($dataNilai);

      // try {
      //     // Insert into Mahasiswa table

      //     // If everything is successful, commit the transaction
      //     DB::commit();
      // } catch (\Exception $e) {
      //     // If an exception occurs, rollback the transaction
      //     DB::rollback();

      //     // Log or print the exception message for debugging
      //     dd($e->getMessage());
      // }
    } catch (\Illuminate\Database\QueryException $e) {
      if ($e->getCode() == '23000') {
        // Tangani kesalahan unik di sini
        session()->flash('error', 'Data Mahasiswa sudah digunakan diimport');
        return redirect()->back();
      } else {
        // Tangani kesalahan lainnya
        session()->flash('error', 'Terjadi kesalahan pada server');
        return redirect()->back();
      }
    }
    return back()->withSuccess('Great! Data has been successfully uploaded.');
  }

  public function filter(Request $request)
  {
    $jurusan = $request->jurusan;
    $angkatan = $request->angkatan;
    $kriterias = $this->get_kriteria();
    $jurusans = $this->get_jurusan();

    $min_year = Mahasiswa::min('angkatan');
    $max_year = Mahasiswa::max('angkatan');
    $this->validate($request, [
      'jurusan' => 'nullable|array',
      'angkatan' => 'nullable|numeric|between:' . $min_year . ',' . $max_year,
    ]);

    $dataJurusan = [];
    foreach ($this->get_jurusan() as $value) {
      array_push($dataJurusan, $value);
    }

    $persamaan = array_intersect($jurusan, $dataJurusan);

    // Filter Jurusan
    $data = Mahasiswa::query();

    if (!empty($jurusan) && $jurusan[0] != 0) {
      $data->whereIn('jurusan', $jurusan);
    }

    // Filter Angkatan
    if (!is_null($angkatan)) {
      $data->where('angkatan', $angkatan);
    }

    $mahasiswas = $data->leftJoin('nilais', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
      ->leftJoin('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
      ->select(
        'mahasiswas.id as mahasiswa_id',
        'mahasiswas.nim',
        'mahasiswas.nama',
        'mahasiswas.status',
        'mahasiswas.jurusan',
        'mahasiswas.angkatan',
        'nilais.nilai',
        'kriterias.nama_kriteria'
      )
      ->orderBy('mahasiswas.nim', 'asc')
      ->get()
      ->filter(function ($value, $key) {
        $year = intval(date('Y')) - intval($value->angkatan);
        if ($year > 1 && $year <= 3) {
          return $value;
        }
      });

    foreach ($mahasiswas as $mahasiswa) {
      $mahasiswaId = $mahasiswa->mahasiswa_id;

      if (!isset($hasil[$mahasiswaId])) {
        $hasil[$mahasiswaId] = (object) [
          'id' => $mahasiswaId,
          'nim' => $mahasiswa->nim,
          'nama' => $mahasiswa->nama,
          'status' => $mahasiswa->status,
          'jurusan' => $mahasiswa->jurusan,
        ];
      }

      if (!empty($mahasiswa->nama_kriteria)) {
        $hasil[$mahasiswaId]->{$mahasiswa->nama_kriteria} = $mahasiswa->nilai;
      }
    }

    return view('content.mahasiswa.index', [
      'kriterias' => $kriterias,
      'data' => $hasil,
      'jurusan' => $jurusans
    ]);
  }

  public function getMahasiswaById($id)
  {
    $kriterias = $this->get_kriteria();

    // $nilaiMahasiswa = Nilai::join('mahasiswas', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
    //   ->select('nilais.*', 'mahasiswas.*')
    //   ->where('mahasiswas.id', $id)
    //   ->get();

    // $mahasiswas = Mahasiswa::leftJoin('nilais', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
    //   ->leftJoin('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
    //   ->select(
    //     'mahasiswas.id as mahasiswa_id',
    //     'mahasiswas.nim',
    //     'mahasiswas.nama',
    //     'mahasiswas.status',
    //     'mahasiswas.jurusan',
    //     'nilais.nilai',
    //     'kriterias.nama_kriteria'
    //   )
    //   ->where('mahasiswas.id', $id)
    //   ->get();

    // foreach ($mahasiswas as $mahasiswa) {
    //   $mahasiswaId = $mahasiswa->mahasiswa_id;

    //   if (!isset($hasil[$mahasiswaId])) {
    //     $hasil[$mahasiswaId] = (object) [
    //       'id' => $mahasiswaId,
    //       'nim' => $mahasiswa->nim,
    //       'nama' => $mahasiswa->nama,
    //       'status' => $mahasiswa->status,
    //       'jurusan' => $mahasiswa->jurusan,
    //     ];
    //   }

    //   if (!empty($mahasiswa->nama_kriteria)) {
    //     $hasil[$mahasiswaId]->{$mahasiswa->nama_kriteria} = $mahasiswa->nilai;
    //   }
    // }
    // var_dump($hasil);
    // return response()->json($hasil);
  }

  // MahasiswaController.php

  public function updateMahasiswa(Request $request, $id)
  {
    try {
      //code...
      $kriterias = $this->get_kriteria();

      $request_validate = [];

      foreach ($kriterias as $kriteria) {
        $request_validate[str_replace(' ', '_', strtolower($kriteria->nama_kriteria)) . '_' . $id] = 'nullable|numeric';
      }

      $request->validate($request_validate);

      // Ambil data mahasiswa dari database berdasarkan ID
      $mahasiswa = Mahasiswa::find($id);

      if (!$mahasiswa) {
        return back()->with('message', 'Mahasiswa not found');
      }

      $datas_nilai = [];
      foreach ($kriterias as $kriteria) {
        $id_input = str_replace(' ', '_', strtolower($kriteria->nama_kriteria)) . '_' . $id;

        if (!key_exists($id_input, $request->input())) {
          break;
        }

        $nilai = Nilai::leftJoin('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
          ->where('nilais.mahasiswa_id', $id)
          ->where('kriterias.nama_kriteria', $kriteria->nama_kriteria)
          ->update(['nilais.nilai' => $request->input($id_input)]);
      }

      return redirect('mahasiswa')->with('update_mahasiswa', 'Data mahasiswa berhasil diperbarui');
    } catch (\Throwable $th) {
      //throw $th;
      return redirect('mahasiswa')->with('update_mahasiswa', $th);
    }
  }

  public function destroy($id)
  {
    $mahasiswa = Mahasiswa::find($id);
    $nilai = Nilai::where('mahasiswa_id', $id);

    if ($mahasiswa) {
      $mahasiswa->delete();
      $nilai->delete();

      // You can return a response if needed
      return redirect('mahasiswa')->with('mahasiswa_deleted', 'Mahasiswa deleted successfully');
    } else {
      // Return a response indicating that the Mahasiswa was not found
      return redirect('mahasiswa')->with('error', 'Mahasiswa not found');
    }
  }
}
