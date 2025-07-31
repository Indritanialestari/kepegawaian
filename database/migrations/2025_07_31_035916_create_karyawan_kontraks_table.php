    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('karyawan_kontrak', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->date('tanggal_lahir')->nullable(); // Ditambahkan ->nullable()
                $table->string('gender')->nullable(); // Ditambahkan ->nullable()
                $table->string('nomor_induk')->unique()->nullable(); // Ditambahkan ->nullable()
                $table->string('jabatan')->nullable(); // Ditambahkan ->nullable()
                $table->string('bagian')->nullable(); // Ditambahkan ->nullable()
                $table->string('unit_kerja')->nullable(); // Ditambahkan ->nullable()
                $table->string('pendidikan')->nullable(); // Ditambahkan ->nullable()
                $table->string('keluarga_status')->nullable(); // Ditambahkan ->nullable()
                $table->integer('keluarga_anak')->nullable(); // Ditambahkan ->nullable()
                $table->string('masa_kerja')->nullable(); // **Diubah dari integer menjadi string** dan Ditambahkan ->nullable()
                $table->date('tanggal_perhitungan')->nullable(); // Ditambahkan ->nullable()
                $table->string('gaji')->nullable(); // Ditambahkan ->nullable()
                $table->string('status')->nullable(); // Ditambahkan ->nullable()
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('karyawan_kontrak');
        }
    };
    