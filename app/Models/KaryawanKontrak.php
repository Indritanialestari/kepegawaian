<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanKontrak extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'karyawan_kontrak';

    // Kolom-kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'nama',
        'tanggal_lahir',
        'gender',
        'nomor_induk',
        'jabatan',
        'bagian',
        'unit_kerja',
        'pendidikan',
        'keluarga_status',
        'keluarga_anak',
        'masa_kerja',
        'tanggal_perhitungan', // Kolom baru
        'gaji',
        'status',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     * Berguna untuk konversi otomatis tipe data saat mengambil dari DB.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_perhitungan' => 'date', // Cast kolom baru sebagai date
    ];
}
