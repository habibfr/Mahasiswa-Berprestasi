<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     
        $mahasiswaData = array();
        for ($i = 1; $i < 101; $i++) {
            if ($i < 10) {
                $nim = '2141010000' . $i;
            } else if ($i < 100) {
                $nim = '214101000' . $i;
            } else {
                $nim = '21410100' . $i;
            }

            $nama = fake()->name();

            $year = '2021';

            $chance_status = random_int(0, 10);

            $status = ($chance_status < 3) ? 'tidak aktif' : 'aktif';

            $jurusan = 'S1 Sistem Informasi';

            array_push($mahasiswaData, [
                'nim' => $nim,
                'nama' => $nama,
                'angkatan' => $year,
                'status' => $status,
                'jurusan' => $jurusan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert data dummy ke dalam tabel 'mahasiswa'
        // Mahasiswa::create($mahasiswaData);
        DB::table('mahasiswas')->insert($mahasiswaData);
    }
}
