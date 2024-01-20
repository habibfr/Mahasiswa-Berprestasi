<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePeringkatPDF()
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

        $pdf = PDF::loadView('content.pdf.peringkat', [
            'kriterias' => $kriterias,
            'matrix' => $hasil,
        ]);

        return $pdf->download('pemilihan_mahasiswa_berprestasi.pdf');
    }
}
