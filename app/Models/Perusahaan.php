<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';

    public $timestamps = false;

    protected $fillable = [
        'nama',
    ];

    public function posisi()
    {
        return $this->hasMany(Posisi::class, 'perusahaan_id');
    }
}
