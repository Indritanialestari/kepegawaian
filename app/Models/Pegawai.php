<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawais'; // sesuaikan dengan nama tabel

    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'gender',
        'kontak',
        'email',
        'alamat',
        'tanggal_masuk',
        'masa_kerja',
        'golongan',
        'gaji',
        'status',
    ];
}


