<?php

namespace Database\Seeders;

use App\Models\User; // Ditambahkan sesuai input Anda
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void // Tipe hint diubah sesuai input Anda
    {
        // Contoh user (boleh dihapus kalau tidak perlu)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Menjalankan seeder untuk Karyawan Tetap dan Karyawan Kontrak
        $this->call([
            PegawaiSeeder::class, // Dipanggil sesuai input Anda
            KaryawanKontrakSeeder::class, // Tetap dipanggil untuk data kontrak
        ]);

        // Baris-baris factory user lama yang sudah tidak digunakan (dikomentari atau dihapus)
        // \App\Models\User::factory(10)->create();
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
