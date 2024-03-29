<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Http\Requests\StoreHasilRequest;
use App\Http\Requests\UpdateHasilRequest;
use App\Models\Kriteria;
use App\Models\Mahasiswa;

class HasilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $hasil = Hasil::leftJoin('mahasiswas', 'mahasiswas.id', '=', 'hasils.mahasiswa_id')
        //     ->where('hasils.status', 'aktif')
        //     ->orderBy('hasils.peringkat', 'asc')
        //     ->distinct()
        //     ->get();

        $hasil = Mahasiswa::orderBy('hasils.peringkat', 'asc')
            ->join('hasils', 'hasils.mahasiswa_id', '=', 'mahasiswas.id')
            ->where('hasils.status', 'aktif')
            ->whereBetween('angkatan', [intval(date('Y')) - 3, intval(date('Y')) - 1])
            ->get();

        return view('content.homepage.index', ['data' => $hasil]);

        // dd(Kriteria::where('nama_kriteria', 'IPK')->where('periode', date('Y'))->subkriteria()->count());
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
    public function store(StoreHasilRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Hasil $hasil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hasil $hasil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHasilRequest $request, Hasil $hasil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hasil $hasil)
    {
        //
    }
}
