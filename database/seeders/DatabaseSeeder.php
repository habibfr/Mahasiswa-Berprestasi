<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(30)->create();

        Kriteria::create([
            'nama_kriteria'=>'IPK',
            'atribut'=>'benefit',
            'bobot'=>0.4
        ]);

        Kriteria::create([
            'nama_kriteria'=>'TOEFL',
            'atribut'=>'benefit',
            'bobot'=>0.2
        ]);
        
        Kriteria::create([
            'nama_kriteria'=>'SSKM',
            'atribut'=>'benefit',
            'bobot'=>0.2
        ]);

        Kriteria::create([
            'nama_kriteria'=>'Karya Tulis',
            'atribut'=>'benefit',
            'bobot'=>0.2
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
