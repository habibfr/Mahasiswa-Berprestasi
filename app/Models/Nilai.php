<?php

namespace App\Models;

use App\Models\Kriteria;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;


    // protected $guarded = ['id'];
    protected $fillable = [
        'nim',
        'kriteria_id',
        'nilai'
    ];


    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
