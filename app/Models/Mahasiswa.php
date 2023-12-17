<?php

namespace App\Models;

use App\Models\Hasil;
use App\Models\Mahasiswa\Nilai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $guarded = ['NIM'];

    protected $with = ['nilai', 'kriteria'];

    public function nilai()
    {
        // return $this->hasOne(Nilai::class, 'mahasiswa_id', 'id');
        return $this->hasMany(Nilai::class, 'nim', 'nim');
    }

    public function hasil()
    {
        return $this->hasMany(Hasil::class, 'nim', 'nim');
    }
}
