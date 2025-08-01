<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNullableFieldsInPegawaisTable extends Migration
{
    public function up()
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->string('nama')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->string('nomor_induk')->nullable()->change();
            $table->string('jabatan')->nullable()->change();
            $table->string('bagian')->nullable()->change();
            $table->string('unit_kerja')->nullable()->change();
            $table->string('pendidikan')->nullable()->change();
            $table->string('klasifikasi')->nullable()->change();
            $table->string('keluarga_status')->nullable()->change();
            $table->string('keluarga_anak')->nullable()->change();
            $table->date('tanggal_masuk')->nullable()->change();
            $table->integer('masa_kerja')->nullable()->change();
            $table->string('golongan')->nullable()->change();
            $table->string('gaji')->nullable()->change();
            $table->string('status')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->string('nama')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->string('gender')->nullable(false)->change();
            $table->string('nomor_induk')->nullable(false)->change();
            $table->string('jabatan')->nullable(false)->change();
            $table->string('bagian')->nullable(false)->change();
            $table->string('unit_kerja')->nullable(false)->change();
            $table->string('pendidikan')->nullable(false)->change();
            $table->string('klasifikasi')->nullable(false)->change();
            $table->string('keluarga_status')->nullable(false)->change();
            $table->string('keluarga_anak')->nullable(false)->change();
            $table->date('tanggal_masuk')->nullable(false)->change();
            $table->integer('masa_kerja')->nullable(false)->change();
            $table->string('golongan')->nullable(false)->change();
            $table->string('gaji')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
        });
    }
}
