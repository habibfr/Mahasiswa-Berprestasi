<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Normalisasi;
use App\Models\Subkriteria;
// use App\Http\Requests\StoreKriteriaRequest;
// use App\Http\Requests\UpdateKriteriaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    try {
      // Ngambil data kriteria
      $data = Kriteria::where('periode', date('Y'))->orderBy('periode', 'desc')->get();
      $total_bobot = Kriteria::where('periode', date('Y'))->orderBy('periode', 'desc')->sum('bobot');

      // Ngirim data
      return view('content.kriteria.index', ['data' => $data, 'judul' => 'Kriteria', 'total_bobot' => $total_bobot]);
    } catch (\Exception $e) {
      // Handle the exception and return an error view with a message
      return view('content.kriteria.index')->with('error', 'Error: ' . $e->getMessage());
    }
  }

  public function test(Request $request)
  {
    try {
      redirect($this->index($request))->with('error', 'Error');
    } catch (\Throwable $th) {
    }
  }

  public function showupkriteria(Request $request)
  {
    // Ngambil data kriteria
    $data = Kriteria::where('id', $request->id)->get();

    // Ngirim data
    return response()->json(['data' => $data]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function subkriteria(Request $request)
  {
    // Mengambil data subkriteria
    $datasub = Subkriteria::where('kriteria_id', $request->id)->get();
    // dd($request->all());
    return response()->json(['datasub' => $datasub]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    try {
      $sum_of_bobot = Kriteria::where('periode', $request->input('periode'))
        ->where('id', '!=', $request->input('id'))
        ->sum('bobot');

      $request->validate([
        'bobot' => 'required|numeric|min:0|max:' . (1 - ($sum_of_bobot)),
        'atribut' => 'required|in:benefit,cost',
        'nama_kriteria' => 'required|string|max:50',
        'periode' => 'required|integer|min:1000|max:9999',
        // Sesuaikan aturan validasi dengan model dan kebutuhan Anda
      ]);

      $kriteria = Kriteria::create($request->except(['_token', 'submit']));
      $subkriteria = [];
      collect($subkriteria);
      $subkriteria['nama_subkriteria'] = $kriteria->nama_kriteria;
      $subkriteria['kriteria_id'] = $kriteria->id;
      $subkriteria = Subkriteria::create($subkriteria);

      return redirect('/kriteria')->with('berhasil_ubah_kriteria', 'Berhasil menambahkan kriteria!');
    } catch (\Throwable $th) {
      return redirect('/kriteria')->with('error', $th->getMessage());
    }
  }

  public function substore(Request $request)
  {
    // dd($request->all());
    Subkriteria::create($request->except(['_token', 'submit']));
    return redirect('/kriteria')->with('berhasil_ubah_kriteria', 'Berhasil menambahkan kriteria!');
  }


  public function subeditshow(Request $request)
  {
    // Mengambil data subkriteria
    $datasub = Subkriteria::where('id', $request->id)->get();
    // dd($request->all());      
    return response()->json(['datasub' => $datasub]);
  }

  /**
   * Display the specified resource.
   */
  public function subedit(Request $request)
  {
    //
    try {
      // Validasi data yang diterima dari formulir
      $request->validate([
        'nama_subkriteria' => 'required|string|max:50',
        'bobot_normalisasi' => 'required|numeric|min:1|max:999',
        // Sesuaikan aturan validasi dengan model dan kebutuhan Anda
      ]);
      $nama_subkriteria = $request->input('nama_subkriteria');
      $bobot_normalisasi = $request->input('bobot_normalisasi');
      // Ambil data user berdasarkan ID
      $subkriteria = Subkriteria::findOrFail($request->id);
      // dd($request->all());
      // Update data subkriteria dengan data baru
      $subkriteria->nama_subkriteria = $nama_subkriteria;
      $subkriteria->bobot_normalisasi = $bobot_normalisasi;
      // Simpan perubahan ke database
      $subkriteria->save();

      // ambil mahasiswa yang aktif dan sesuai peraturan
      Mahasiswa::where('status', 'aktif')
        ->whereBetween('angkatan', [intval(date('Y')) - 3, intval(date('Y')) - 1])
        ->get()
        ->map(function ($mahasiswa) use ($subkriteria) {
          // kalikan dengan bobot normalisasi dan jumlah ulang nilai di nilai sesuai mahasiswa dan subkriteria
          $nilai = Nilai::join('subkriterias', 'subkriterias.id', '=', 'nilais.subkriteria_id')
            ->where([
              ['mahasiswa_id', $mahasiswa->id],
              ['kriteria_id', $subkriteria->kriteria_id],
            ])
            ->selectRaw('SUM(nilais.nilai * subkriterias.bobot_normalisasi) as total_nilai')
            ->get();
          // dd($nilai->first()->total_nilai);

          // update nilai di normalisasi sesuai kriteria
          $normalisasi = Normalisasi::where([
            ['mahasiswa_id', $mahasiswa->id],
            ['kriteria_id', $subkriteria->kriteria_id]
          ])->update(['nilai' => $nilai->first()->total_nilai]);
        });

      // Redirect atau berikan respons sesuai kebutuhan aplikasi Anda
      return redirect()->route('kriteria')->with('berhasil_ubah_subkriteria', 'Data kriteria berhasil diperbarui');
    } catch (\Exception $e) {
      return redirect()->route('kriteria')->with('error', $e->getMessage());
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Request $request)
  {

    // dd($request->all());
    try {
      // Kode update dan penyimpanan di sini
      //
      // dd($request->except(['_token','submit']));
      $sum_of_bobot = Kriteria::where('periode', $request->input('periode'))
        ->where('id', '!=', $request->input('id'))
        ->sum('bobot');

      // dd($sum_of_bobot);

      // Validasi data yang diterima dari formulir
      $request->validate([
        'bobot' => 'required|numeric|min:0|max:' . (1 - ($sum_of_bobot)),
        'atribut' => 'required|in:Benefit,Cost',
        'nama_kriteria' => 'required|string|max:50',
        'periode' => 'required|integer|min:1000|max:9999',
        // Sesuaikan aturan validasi dengan model dan kebutuhan Anda
      ]);

      // Ambil data dari formulir
      $bobot = $request->input('bobot');
      $atribut = $request->input('atribut');
      $nama_kriteria = $request->input('nama_kriteria');
      $periode = $request->input('periode');

      // Ambil data user berdasarkan ID
      $user = Kriteria::findOrFail($request->id);

      // Update data user dengan data baru
      $user->bobot = $bobot;
      $user->atribut = $atribut;
      $user->nama_kriteria = $nama_kriteria;
      $user->periode = $periode;
      // Update atribut lain sesuai kebutuhan

      // Simpan perubahan ke database
      $user->save();

      // Redirect atau berikan respons sesuai kebutuhan aplikasi Anda
      return redirect()->route('kriteria')->with('berhasil_ubah_kriteria', 'Data kriteria berhasil diperbarui');
    } catch (\Exception $e) {
      return redirect()->route('kriteria')->with('error', $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Kriteria $kriteria)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    // Validate the request if needed

    // Extract the ID from the request
    $id = $request->input('id');

    // Find the record by ID
    $data = Kriteria::find($id);

    // Check if the record exists
    if ($data) {
      // Delete the record
      $data->delete();
      Subkriteria::where('kriteria_id', $id)->delete();

      // Optionally, you may want to add a success message or redirect
      return redirect()->route('kriteria')->with('berhasil_delete_kriteria', 'Kriteria berhasil dihapus!');
    } else {
      // Record not found, you may want to handle this case
      return redirect()->route('kriteria')->with('berhasil_delete_kriteria', 'Record not found!');
    }
  }

  public function destroysub(Request $request)
  {

    // dd($request->all());

    // Check if the record exists
    if ($request) {
      // Delete the record
      Subkriteria::where('id', $request->id)->delete();

      // Optionally, you may want to add a success message or redirect
      return redirect()->route('kriteria')->with('berhasil_delete_subkriteria', 'Kriteria berhasil dihapus!');
    } else {
      // Record not found, you may want to handle this case
      return redirect()->route('kriteria')->with('berhasil_delete_subkriteria', 'Record not found!');
    }
  }
}
