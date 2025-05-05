<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Praktikan extends Model
{
    Use HasFactory, SoftDeletes;

    protected $fillable = ['nama', 'nim', 'fingerprint_id'];

    public function kehadiran()
    {
        return $this->hasMany(KehadiranPraktikan::class);
    }

    public function kelas()
    {
        return $this->hasMany(KelasPraktikan::class);
    }
}
