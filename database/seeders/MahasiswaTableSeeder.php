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
        $arr_of_year = [2020, 2021, 2022, 2023];
        $arr_of_jurusan = [
            ['kode' => '39010', 'label' => 'D3 Sistem Informasi'],
            ['kode' => '41010', 'label' => 'S1 Sistem Informasi'],
        ];
        foreach ($arr_of_year as $year) {
            $year_of_nim = substr($year, 2, 2);

            foreach ($arr_of_jurusan as $jurusan) {
                $mahasiswaData = [];

                for ($i = 1; $i < rand(40, 91); $i++) {
                    $nim = $year_of_nim . $jurusan['kode'] . str_pad($i, 4, 0, STR_PAD_LEFT);

                    $nama = fake()->name();

                    $chance_status = random_int(0, 10);

                    $status = ($chance_status < 2) ? 'tidak aktif' : 'aktif';

                    // array_push($mahasiswaData, 
                    Mahasiswa::create(
                        [
                            // 'id' => uuid_create(),
                            'nim' => $nim,
                            'nama' => $nama,
                            'angkatan' => $year,
                            'status' => $status,
                            'jurusan' => $jurusan['label'],
                            // 'created_at' => now(),
                            // 'updated_at' => now(),
                        ]
                        );
                    // );
                }

                // Insert data dummy ke dalam tabel 'mahasiswa'
                // Mahasiswa::createMany($mahasiswaData);
                // DB::table('mahasiswas')->insert($mahasiswaData);
            }
        }
    }
}
