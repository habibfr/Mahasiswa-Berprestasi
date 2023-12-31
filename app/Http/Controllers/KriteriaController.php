<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Http\Requests\StoreKriteriaRequest;
use App\Http\Requests\UpdateKriteriaRequest;
use GuzzleHttp\Psr7\Request;

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
  public function store(StoreKriteriaRequest $request)
  {
    //

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
  public function edit(Kriteria $kriteria)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateKriteriaRequest $request, Kriteria $kriteria)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Kriteria $kriteria)
  {
    //
  }
}
