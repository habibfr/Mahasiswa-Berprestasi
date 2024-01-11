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
    return Kriteria::where('periode', '2023')->get();
  }
  public function index()
  {
    // $jurusan = Mahasiswa::distinct()
    //   ->pluck('jurusan');

    // $kriterias = Kriteria::where('periode', '2023')->get();

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
        'nilais.nilai',
        'kriterias.nama_kriteria'
      )
      ->get();

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
          'jurusan' => $sheet->getCell('D' . $row)->getValue(),
          'email' => $sheet->getCell('E' . $row)->getValue(),
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
        'nilais.nilai',
        'kriterias.nama_kriteria'
      )
      ->get();

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

    return view('content.mahasiswa.index', ['kriterias' => $kriterias, 'data' => $hasil, 'jurusan' => $jurusans]);
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
    $kriterias = $this->get_kriteria();

    $request_validate = [
      // 'nim_mhs' => 'required|string|max:15',
      // 'nama_mhs' => 'required|string|max:255',
      // 'jurusan_mhs' => 'required',
    ];

    foreach ($kriterias as $kriteria) {
      $request_validate[str_replace(' ', '_', strtolower($kriteria->nama_kriteria)) . '_' . $id] = 'nullable|numeric';
    }

    // var_dump($request_validate);
    // var_dump($request->input());

    $request->validate($request_validate);

    // Ambil data mahasiswa dari database berdasarkan ID
    $mahasiswa = Mahasiswa::find($id);

    if (!$mahasiswa) {
      return back()->with('message', 'Mahasiswa not found');
    }

    // Perbarui data mahasiswa
    // $mahasiswa->nim = $request->input('nim');
    // $mahasiswa->nama = $request->input('nama');
    // $mahasiswa->jurusan = $request->input('jurusan');

    $datas_nilai = [];
    foreach ($kriterias as $kriteria) {
      $id_input = str_replace(' ', '_', strtolower($kriteria->nama_kriteria)) . '_' . $id;

      if (!key_exists($id_input, $request->input())) {
        break;
      }

      // array_push($datas_nilai, $kriteria);

      $nilai = Nilai::leftJoin('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
        ->where('nilais.mahasiswa_id', $id)
        ->where('kriterias.nama_kriteria', $kriteria->nama_kriteria)
        ->first();

      // if ($nilai) {
      // Perbarui nilai atribut sesuai dengan data yang diterima melalui $request
      $data_nilai = [
        'mahasiswa_id' => $id,
        'kriteria_id' => $kriteria->id,
        'nilai' => $request->input($id_input),
        'created_at' => now(),
        'updated_at' => now(),
      ];
      // $nilai->ipk = $request->input('ipk');
      // $nilai->toefl = $request->input('toefl');
      // $nilai->karya_tulis = $request->input('karya_tulis');
      // $nilai->sskm = $request->input('sskm');
      // Perbarui kolom lainnya sesuai kebutuhan

      // Simpan perubahan nilai ke database
      // $nilai->save();
      // $mahasiswa->save();
      array_push($datas_nilai, $data_nilai);
    }

    // var_dump($datas_nilai);
    Nilai::insert($datas_nilai);
    // }

    return back()->with('message', 'Data mahasiswa berhasil diperbarui');
  }

  public function destroy($id)
  {
    $mahasiswa = Mahasiswa::find($id);
    $nilai = Nilai::where('mahasiswa_id', $id)->first();

    if ($mahasiswa) {
      $mahasiswa->delete();
      $nilai->delete();

      // You can return a response if needed
      return response()->json(['message' => 'Mahasiswa deleted successfully']);
    } else {
      // Return a response indicating that the Mahasiswa was not found
      return response()->json(['error' => 'Mahasiswa not found'], 404);
    }
  }
}
