<?php

namespace App\Models;

use App\Models\Kriteria;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nilai extends Model
{
  use HasFactory, SoftDeletes;

  // protected $guarded = ['id'];
  protected $fillable = ['mahasiswa_id', 'kriteria_id', 'nilai'];

  // protected $nullable = ['deleted_at'];

  protected $with = ['mahasiswa', 'kriteria'];

  public function mahasiswa()
  {
    return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id');
  }

  public function kriteria()
  {
    return $this->belongsTo(Kriteria::class, 'kriteria_id', 'id');
  }
}
