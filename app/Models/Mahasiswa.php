<?php

namespace App\Models;

use App\Models\Hasil;
use App\Models\Nilai;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
  use HasFactory, HasUuids, SoftDeletes;

  protected $guarded = ['id'];

  // protected $with = ['nilai'];

  public function nilai()
  {
    // return $this->hasOne(Nilai::class, 'mahasiswa_id', 'id');
    // return $this->hasMany(Nilai::class, 'mahasiswa_id', 'nim');
    return $this->hasMany(Nilai::class, 'mahasiswa_id', 'id');
  }

  public function hasil()
  {
    return $this->hasMany(Hasil::class);
  }
}
