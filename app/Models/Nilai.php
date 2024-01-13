<?php

namespace App\Models;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nilai extends Model
{
  use HasFactory, SoftDeletes;

  // protected $guarded = ['id'];
  protected $fillable = ['mahasiswa_id', 'subkriteria_id', 'nilai'];

  // protected $nullable = ['deleted_at'];

  // protected $with = ['mahasiswa', 'subkriteria'];

  public function mahasiswa(): BelongsTo
  {
    return $this->belongsTo(Mahasiswa::class);
  }

  public function subkriteria(): BelongsTo
  {
    return $this->belongsTo(Subkriteria::class);
  }
}
