<?php

namespace Database\Seeders;

use App\Models\Hasil;
use App\Models\Mahasiswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HasilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $peringkat = 1;
        $poin = 100;

        foreach (Mahasiswa::where('status', 'aktif')->limit(20)->cursor() as $data) {
            DB::table('hasils')->insert([
                'mahasiswa_id' => $data->id,
                'peringkat' => $peringkat,
                'poin' => $poin,
                'status' => $peringkat < 11 ? 'aktif' : 'tidak aktif',
                'periode' => '2023',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $peringkat++;
            $poin--;
        }
    }
}
