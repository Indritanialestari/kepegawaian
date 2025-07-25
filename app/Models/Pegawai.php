<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawais';

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'gender',
        'nomor_induk',
        'jabatan',
        'bagian',
        'unit_kerja',
        'pendidikan',
        'klasifikasi',
        'keluarga_status',
        'keluarga_anak',
        'tanggal_masuk',
        'masa_kerja',
        'golongan',
        'gaji',
        'status',
    ];
}
