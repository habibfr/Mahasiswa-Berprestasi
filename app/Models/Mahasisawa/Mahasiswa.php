<?php

namespace App\Models\Mahasisawa;

use App\Models\Mahasiswa\Nilai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'mahasiswa_id', 'id');
    }



}
