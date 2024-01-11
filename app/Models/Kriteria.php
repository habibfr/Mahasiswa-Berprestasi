<?php

namespace App\Models;

use App\Models\Nilai;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kriteria extends Model
{
  use HasFactory, HasUuids, SoftDeletes;

  protected $guarded = ['id'];

  protected $fillable = ['bobot', 'atribut', 'nama_kriteria','periode'];

  public function nilai()
  {
    return $this->hasMany(Nilai::class);
  }
}
