<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->string('gender');

            // Kolom baru
            $table->string('nomor_induk');
            $table->string('jabatan');
            $table->string('bagian');
            $table->string('unit_kerja');
            $table->string('pendidikan');
            $table->string('klasifikasi');
            $table->string('keluarga_status');
            $table->string('keluarga_anak');

            // Kolom yang tetap dipakai
            $table->date('tanggal_masuk');
            $table->integer('masa_kerja');
            $table->string('golongan');
            $table->string('gaji');
            $table->string('status');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
