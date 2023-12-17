<?php

namespace Database\Seeders;

use App\Models\Mahasisawa\Mahasiswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // $mahasiswaData = [
        //     [
        //         'nim' => '21410100088',
        //         'nama' => 'Budi Sudarjo',
        //         'email'=> '21410100088@briliant.co',
        //         'jurusan' => 'S1 Sistem Informasi',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'nim' => '21410100089',
        //         'nama' => 'Ceci Indriani',
        //         'email'=> '21410100089@briliant.co',
        //         'jurusan' => 'S1 Sistem Informasi',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'nim' => '21410100090',
        //         'nama' => 'Dedi Suhendar',
        //         'email'=> '21410100090@briliant.co',
        //         'jurusan' => 'S1 Sistem Informasi',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // ];
        $mahasiswaData=array();
        array_push($mahasiswaData, [
            'nim'=>'21410100001',
            'nama'=>fake()->name(),
            'angkatan'=>'2021',
            'status'=>'aktif',
            'jurusan'=>'Sistem Informasi'
        ]);

        // Insert data dummy ke dalam tabel 'mahasiswa'
        Mahasiswa::insert($mahasiswaData);
    }
}
