<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
// use App\Http\Requests\StoreKriteriaRequest;
// use App\Http\Requests\UpdateKriteriaRequest;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
    //ngambil data
    $data = Kriteria::all();
    //ngirim data
    return view('content.kriteria.index', ['data' => $data, 'judul' => 'Kriteria']);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //

  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
    // dd($request->except(['_token','submit']));
    Kriteria::create($request->except(['_token', 'submit']));
    return redirect('/kriteria')->with('berhasil_ubah_kriteria', 'Berhasil menambahkan kriteria!');
  }

  /**
   * Display the specified resource.
   */
  public function show(Kriteria $kriteria)
  {
    //
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
        ->where('nama_kriteria', '!=', $request->input('nama_kriteria'))
        ->sum('bobot');

      // dd($sum_of_bobot);

      // Validasi data yang diterima dari formulir
      $request->validate([
        'bobot' => 'required|numeric|min:0|max:' . (1 - $sum_of_bobot),
        'atribut' => 'required|in:benefit,cost',
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
      return redirect()->route('kriteria')->with('berhasil_ubah_kriteria', $e->getMessage());
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

      // Optionally, you may want to add a success message or redirect
      return redirect()->route('kriteria')->with('berhasil_delete_kriteria', 'Kriteria berhasil dihapus!');
    } else {
      // Record not found, you may want to handle this case
      return redirect()->route('kriteria')->with('berhasil_delete_kriteria', 'Record not found!');
    }
  }
}
