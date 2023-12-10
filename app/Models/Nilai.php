<?php

namespace App\Models\Mahasiswa;

use App\Models\Kriteria;
use App\Models\Mahasisawa\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;


    // protected $guarded = ['id'];
    // protected $fillable = [
    //     'nim',
    //     'IPK',
    //     'SSKM',
    //     'TOEFL',
    // ];


    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kriteria() {
        return $this->belongsTo(Kriteria::class);
    }
}
