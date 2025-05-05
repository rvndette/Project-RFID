<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Praktikum extends Model
{
    Use HasFactory, SoftDeletes;

    protected $fillable = ['kelas', 'matkul', 'lab', 'waktu_mulai', 'waktu_selesai'];

    public function pertemuans()
    {
        return $this->hasMany(Pertemuan::class);
    }

    public function kelasPraktikan()
    {
        return $this->hasMany(KelasPraktikan::class);
    }

    public function kelasAsisten()
    {
        return $this->hasMany(KelasAsisten::class);
    }
}
