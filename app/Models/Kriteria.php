<?php

namespace App\Models;

use App\Models\Nilai;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kriteria extends Model
{
  use HasFactory, HasUuids, SoftDeletes;

  protected $guarded = ['id'];

  protected $fillable = ['bobot', 'atribut', 'nama_kriteria', 'periode'];

  protected $with = ['subkriteria', 'nilai', 'normalisasi'];

  public function nilai()
  {
    return $this->hasMany(Nilai::class);
  }

  public function subkriteria()
  {
    return $this->hasMany(Subkriteria::class);
  }

  public function normalisasi()
  {
    return $this->hasMany(Normalisasi::class, 'kriteria_id', 'id');
  }
}
