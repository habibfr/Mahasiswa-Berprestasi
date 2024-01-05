<?php

namespace App\Models;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
  use HasFactory;

  protected $with = ['mahasiswas'];

  public function mahasiswa()
  {
    return $this->belongsTo(Mahasiswa::class);
  }
}
