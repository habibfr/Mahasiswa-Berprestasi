<?php

namespace App\Models;

use App\Models\Nilai;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
  use HasFactory;
  use HasUuids;

  protected $guarded = ['id'];

  protected $fillable = ['bobot', 'atribut', 'nama_kriteria','periode'];

  public function nilai()
  {
    return $this->hasMany(Nilai::class);
  }
}
