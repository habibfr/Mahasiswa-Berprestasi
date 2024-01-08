<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\Mahasiswa\Nilai;
use App\Models\Mahasiswa;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
  public function index()
  {
    $jurusan = Mahasiswa::distinct()
      ->pluck('jurusan');

    $kriterias = Kriteria::where('periode', '2023')->get();

    $hasil = [];

    $mahasiswas = Mahasiswa::leftJoin('nilais', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
      ->leftJoin('kriterias', 'kriterias.id', '=', 'nilais.kriteria_id')
      ->select(
        'mahasiswas.id as mahasiswa_id',
        'mahasiswas.nim',
        'mahasiswas.nama',
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
        ];
      }

      if (!empty($mahasiswa->nama_kriteria)) {
        $hasil[$mahasiswaId]->{$mahasiswa->nama_kriteria} = $mahasiswa->nilai;
      }

      $hasil[$mahasiswaId]->poin = $mahasiswa->poin;
    }

    // dd($hasil);

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
    if ($request->jurusan == 0 && $request->angkatan == null) {
      $data = Mahasiswa::all();

      return view('content.mahasiswa.index', compact('data'))->with('kosong', 'Data jangan kosong');
    } else {
      $this->validate($request, [
        'jurusan' => 'nullable|numeric|max:3',
        'angkatan' => 'nullable|numeric|min:2015|max:2023',
      ]);
    }

    $dataJurusan = ['S1 Sistem Informasi', 'S1 Desain Komunikasi Visual', 'S1 Teknik Komputer'];

    if ($request->jurusan != 0) {
      $jurusan = $dataJurusan[$request->jurusan - 1];
    }

    $angkatan = substr($request->angkatan, 2, 3);

    if ($request->jurusan == 0) {
      $data = Mahasiswa::where('nim', 'like', $angkatan . '%')->get();
      return view('content.mahasiswa.index', compact('data'));
    }

    if ($request->angkatan == null) {
      $data = Mahasiswa::where('jurusan', 'like', $jurusan)->get();
      return view('content.mahasiswa.index', compact('data'));
    }

    $data = Mahasiswa::where('jurusan', 'like', $jurusan)
      ->where('nim', 'like', $angkatan . '%')
      ->get();
    return view('content.mahasiswa.index', compact('data'));
  }

  public function getMahasiswaById($id)
  {
    // $data = Mahasiswa::find($id);
    $nilaiMahasiswa = Nilai::join('mahasiswas', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
      ->select('nilais.*', 'mahasiswas.*')
      ->where('mahasiswas.id', $id)
      ->get();

    return response()->json($nilaiMahasiswa);
  }

  // MahasiswaController.php

  public function updateMahasiswa(Request $request, $id)
  {
    $request->validate([
      'nim' => 'required|string|max:15',
      'nama' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'jurusan' => 'required',
      'ipk' => 'nullable|numeric',
      'sskm' => 'nullable|numeric',
      'toefl' => 'nullable|numeric',
      'karya_tulis' => 'nullable|numeric',
    ]);

    // Ambil data mahasiswa dari database berdasarkan ID
    $mahasiswa = Mahasiswa::find($id);

    if (!$mahasiswa) {
      return response()->json(['message' => 'Mahasiswa not found'], 404);
    }

    // Perbarui data mahasiswa
    $mahasiswa->nim = $request->input('nim');
    $mahasiswa->nama = $request->input('nama');
    $mahasiswa->email = $request->input('email');
    $mahasiswa->jurusan = $request->input('jurusan');

    $nilai = Nilai::where('mahasiswa_id', $id)->first();

    // if ($nilai) {
    // Perbarui nilai atribut sesuai dengan data yang diterima melalui $request
    $nilai->ipk = $request->input('ipk');
    $nilai->toefl = $request->input('toefl');
    $nilai->karya_tulis = $request->input('karya_tulis');
    $nilai->sskm = $request->input('sskm');
    // Perbarui kolom lainnya sesuai kebutuhan

    // Simpan perubahan nilai ke database
    $nilai->save();
    $mahasiswa->save();
    // }

    return response()->json(['message' => 'Data mahasiswa berhasil diperbarui']);
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
