<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subkriteria extends Model
{
    protected $fillable = [
        "kriteria_id",
        'nama_subkriteria',
        'bobot_normalisasi'
    ];
    use HasFactory, HasUuids, SoftDeletes;

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
