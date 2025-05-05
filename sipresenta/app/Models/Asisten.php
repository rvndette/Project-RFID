<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asisten extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nama', 'nim', 'rfid_id'];

    public function kehadiran()
    {
        return $this->hasMany(KehadiranAsisten::class);
    }

    public function kelas()
    {
        return $this->hasMany(KelasAsisten::class);
    }
}
