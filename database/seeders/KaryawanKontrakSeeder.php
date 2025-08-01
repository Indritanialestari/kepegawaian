<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KaryawanKontrak; // Import model KaryawanKontrak
use Faker\Factory as Faker; // Import Faker
use Carbon\Carbon; // Untuk manipulasi tanggal

class KaryawanKontrakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pastikan tabel kosong sebelum seeding untuk menghindari duplikasi
        KaryawanKontrak::truncate();

        $faker = Faker::create('id_ID'); // Menggunakan Faker dengan lokal Indonesia

        // Data pertama (yang sebelumnya Anda berikan)
        $data1 = [
            ['nama' => 'DADANG HUZAZI', 'keluarga_status_raw' => 'K', 'bagian_raw' => 'Koordinator Satpam Pusat', 'unit_kerja' => 'Pusat', 'masa_kerja_d1' => '20', 'gaji' => '2.707.871', 'tanggal_lahir_d1' => '07/07/1968'],
            ['nama' => 'DIDIEN FIRDAUS, Amd.', 'keluarga_status_raw' => 'K', 'bagian_raw' => 'Staf Produksi Pusat', 'unit_kerja' => 'Pusat', 'masa_kerja_d1' => '20', 'gaji' => '2.707.871', 'tanggal_lahir_d1' => null],
            ['nama' => 'IING IRWANDI', 'keluarga_status_raw' => 'K', 'bagian_raw' => 'Staf Produksi ( Operator )', 'unit_kerja' => 'Pusat', 'masa_kerja_d1' => '20', 'gaji' => '2.407.871', 'tanggal_lahir_d1' => '10-06-1972'],
            ['nama' => 'IRFAN KOMARA', 'keluarga_status_raw' => 'K', 'bagian_raw' => 'Staf Distribusi dan Penyambungan', 'unit_kerja' => 'Jatitujuh', 'masa_kerja_d1' => '20', 'gaji' => '2.407.871', 'tanggal_lahir_d1' => '14-03-1974'],
            ['nama' => 'LILI KASLI MUCHYIDIN', 'keluarga_status_raw' => 'K', 'bagian_raw' => 'Staf Produksi IKK Dawuan', 'unit_kerja' => 'Kadipaten', 'masa_kerja_d1' => '20', 'gaji' => '2.707.871', 'tanggal_lahir_d1' => '31/12/1967'],
            ['nama' => 'M. ODIN NURODIN', 'keluarga_status_raw' => 'K', 'bagian_raw' => 'Staf Produksi', 'unit_kerja' => 'Jatitujuh', 'masa_kerja_d1' => '20', 'gaji' => '2.707.871', 'tanggal_lahir_d1' => null],
            ['nama' => 'MEMED', 'keluarga_status_raw' => 'K', 'bagian_raw' => 'Staf Produksi Pusat', 'unit_kerja' => 'Pusat', 'masa_kerja_d1' => '20', 'gaji' => '2.707.871', 'tanggal_lahir_d1' => '25-12-1976'],
            ['nama' => 'NOK IDA', 'keluarga_status_raw' => 'TK', 'bagian_raw' => 'Staf Pembukuan & Keu', 'unit_kerja' => 'Sukaraja', 'masa_kerja_d1' => '20', 'gaji' => '2.707.871', 'tanggal_lahir_d1' => '13-05-1982'],
            ['nama' => 'RIA CYLVIA SASMITA', 'keluarga_status_raw' => 'K', 'bagian_raw' => 'Staf Umum', 'unit_kerja' => 'Pusat', 'masa_kerja_d1' => '20', 'gaji' => '2.407.871', 'tanggal_lahir_d1' => '07-06-1984'],
            ['nama' => 'SLAMET HERMAWAN', 'keluarga_status_raw' => 'K', 'bagian_raw' => 'Satpam', 'unit_kerja' => 'Pusat', 'masa_kerja_d1' => '20', 'gaji' => '2.707.871', 'tanggal_lahir_d1' => null],
            ['nama' => 'SITI NUR\'AIDZA ADIANI', 'keluarga_status_raw' => 'TK', 'bagian_raw' => 'Staf Kasir', 'unit_kerja' => 'Majalengka', 'masa_kerja_d1' => '20', 'gaji' => '2.257.871', 'tanggal_lahir_d1' => null],
            ['nama' => 'DIKY ROZIKIN NURIANA', 'keluarga_status_raw' => 'TK', 'bagian_raw' => 'Staf Baca Meter', 'unit_kerja' => 'Majalengka', 'masa_kerja_d1' => '20', 'gaji' => '2.257.871', 'tanggal_lahir_d1' => null],
            ['nama' => 'NUKI JUNIAWAN', 'keluarga_status_raw' => 'TK', 'bagian_raw' => 'Staf Baca Meter', 'unit_kerja' => 'Majalengka', 'masa_kerja_d1' => '20', 'gaji' => '2.257.871', 'tanggal_lahir_d1' => null],
            ['nama' => 'MUHAMAD ALDI FARHAN PERMADI', 'keluarga_status_raw' => 'TK', 'bagian_raw' => 'Satpam', 'unit_kerja' => 'Pusat', 'masa_kerja_d1' => '20', 'gaji' => '2.257.871', 'tanggal_lahir_d1' => null],
            ['nama' => 'ARIF HIMAWAN', 'keluarga_status_raw' => 'TK', 'bagian_raw' => 'Staf Baca Meter', 'unit_kerja' => 'Sukahaji', 'masa_kerja_d1' => '20', 'gaji' => '2.257.871', 'tanggal_lahir_d1' => null],
            ['nama' => 'DEDE TAUFIQ', 'keluarga_status_raw' => 'TK', 'bagian_raw' => 'Staf Baca Meter', 'unit_kerja' => 'Panyingkiran', 'masa_kerja_d1' => '20', 'gaji' => '2.257.871', 'tanggal_lahir_d1' => null],
            ['nama' => 'MUHAMAD RAFI SETIAWAN', 'keluarga_status_raw' => 'TK', 'bagian_raw' => 'Staf Baca Meter', 'unit_kerja' => 'Jatitujuh', 'masa_kerja_d1' => '20', 'gaji' => '2.257.871', 'tanggal_lahir_d1' => null],
        ];

        // Data kedua (yang baru Anda berikan)
        $data2 = [
            ['nama' => 'SLAMET HERMAWAN', 'tempat_lahir' => 'Crb', 'tanggal_lahir' => '01/08/1970', 'tanggal_masuk_d2' => '02/05/2005', 'bagian_raw' => 'Satpam', 'masa_kerja_d2' => '20.2', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 55, 'pendidikan_lanjutan' => 'SLTP', 'alamat' => 'Blok Salasa Rt/Rw 002/003 Desa Tegal Sari Kec : Maja', 'nik' => null, 'phone' => null],
            ['nama' => 'M. ODIN NURODIN', 'tempat_lahir' => 'Mjk', 'tanggal_lahir' => '10/06/1972', 'tanggal_masuk_d2' => '01/01/2010', 'bagian_raw' => 'Staf Produksi', 'masa_kerja_d2' => '15.5', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 53, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => 'Panyingkiran Jatitujuh', 'nik' => null, 'phone' => null],
            ['nama' => 'NOK IDA', 'tempat_lahir' => 'Mjk', 'tanggal_lahir' => '14/03/1974', 'tanggal_masuk_d2' => '01/07/2011', 'bagian_raw' => 'Staf Pelaksana', 'masa_kerja_d2' => '14.0', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 51, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => 'Blok Senen Rt/Rw 004/002 Ds Ranji wetan Kec.Kasokandel', 'nik' => null, 'phone' => null],
            ['nama' => 'DIDIEN FIRDAUS', 'tempat_lahir' => 'Smr', 'tanggal_lahir' => '25/12/1976', 'tanggal_masuk_d2' => '01/10/2014', 'bagian_raw' => 'Staf Produksi Pusat', 'masa_kerja_d2' => '10.8', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 49, 'pendidikan_lanjutan' => 'D.III', 'alamat' => 'Jln Pengadilan Rt/Rw 003/009 Kel: Majalengka Kulon Kec: Mjk', 'nik' => null, 'phone' => null],
            ['nama' => 'IRFAN KOMARA', 'tempat_lahir' => 'Mjk', 'tanggal_lahir' => '13/05/1982', 'tanggal_masuk_d2' => '01/08/2018', 'bagian_raw' => 'Staf Distribusi dan Penyambungan', 'masa_kerja_d2' => '6.9', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 43, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => 'Blok Selasa Rt 002/001 Ds Panyingkiran Kec Jatitujuh', 'nik' => null, 'phone' => null],
            ['nama' => 'IING IRWANDI', 'tempat_lahir' => 'Mjk', 'tanggal_lahir' => '07/06/1984', 'tanggal_masuk_d2' => '01/09/2018', 'bagian_raw' => 'Staf Produksi Pusat', 'masa_kerja_d2' => '6.8', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 41, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => 'BlokGunung Windu Rt 06/ 03 Ds Cipicung Kec Maja Kab Mjk', 'nik' => null, 'phone' => null],
            ['nama' => 'RIA CYLVIA SASMITA', 'tempat_lahir' => 'Bdg', 'tanggal_lahir' => '31/05/1985', 'tanggal_masuk_d2' => '01/03/2020', 'bagian_raw' => 'Staf Umum', 'masa_kerja_d2' => '5.3', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 40, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => 'Komplek bumi Asri Jl Mekar V Blok 4 No C-13 Marga Asih Bdg', 'nik' => null, 'phone' => null],
            ['nama' => 'M. Rafi Setiawan', 'tempat_lahir' => 'Idm', 'tanggal_lahir' => '16/03/1996', 'tanggal_masuk_d2' => '01/08/2024', 'bagian_raw' => 'Staf Pembaca Meter', 'masa_kerja_d2' => '0.9', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 29, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => null, 'nik' => null, 'phone' => null],
            ['nama' => 'Arif Himawan', 'tempat_lahir' => 'Mjk', 'tanggal_lahir' => '10/08/1994', 'tanggal_masuk_d2' => '01/08/2024', 'bagian_raw' => 'Staf Pembaca Meter', 'masa_kerja_d2' => '0.9', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 31, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => null, 'nik' => null, 'phone' => null],
            ['nama' => 'Dede Taufiq Hidayat', 'tempat_lahir' => 'Mjk', 'tanggal_lahir' => '28/08/1998', 'tanggal_masuk_d2' => '01/08/2024', 'bagian_raw' => 'Staf Pembaca Meter', 'masa_kerja_d2' => '0.9', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 27, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => null, 'nik' => null, 'phone' => null],
            ['nama' => 'M. Aldi Farhan P.', 'tempat_lahir' => 'Mjk', 'tanggal_lahir' => '06/06/1999', 'tanggal_masuk_d2' => '01/08/2024', 'bagian_raw' => 'Satpam', 'masa_kerja_d2' => '0.9', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 26, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => null, 'nik' => null, 'phone' => null],
            ['nama' => 'Jalaludin', 'tempat_lahir' => 'Mjk', 'tanggal_lahir' => '10/02/1995', 'tanggal_masuk_d2' => '01/01/2025', 'bagian_raw' => 'Pembaca Meter', 'masa_kerja_d2' => '0.5', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 30, 'pendidikan_lanjutan' => 'SLTA', 'alamat' => 'PADAREK RT/RW 003/007 DESA PADAREK Kec. Lemahsugih', 'nik' => null, 'phone' => null],
            ['nama' => 'M. Reza Maulana', 'tempat_lahir' => 'Mjk', 'tanggal_lahir' => '28/01/1995', 'tanggal_masuk_d2' => '01/01/2025', 'bagian_raw' => 'Pembukuan', 'masa_kerja_d2' => '0.5', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 30, 'pendidikan_lanjutan' => 'SMK', 'alamat' => 'JL. MARGATAPA Gg WARUNG JAMBU 02 RT/RW 01/01', 'nik' => null, 'phone' => null],
            ['nama' => 'Aldzikrilla Pramudyasa', 'tempat_lahir' => 'Smd', 'tanggal_lahir' => '26/07/1999', 'tanggal_masuk_d2' => '01/01/2025', 'bagian_raw' => 'Staf Pembaca Meter', 'masa_kerja_d2' => '0.5', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 26, 'pendidikan_lanjutan' => 'SMA', 'alamat' => 'Lingk. Sangraja RT 02/RW 01 Kel. Cigasong Kec. Cigasong Kab. Majalengka', 'nik' => null, 'phone' => null],
            ['nama' => 'Ahmed Mulya Permana', 'tempat_lahir' => 'Mjl', 'tanggal_lahir' => '05/05/1997', 'tanggal_masuk_d2' => '01/01/2025', 'bagian_raw' => 'Staf Pembaca Meter', 'masa_kerja_d2' => '0.5', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 28, 'pendidikan_lanjutan' => 'SMK', 'alamat' => 'JL MANGGIS 6 NO 173 PERUM BCA SUKAHAJI', 'nik' => null, 'phone' => null],
            ['nama' => 'Bambang Septiadi Santana', 'tempat_lahir' => 'Mjl', 'tanggal_lahir' => '18/09/1995', 'tanggal_masuk_d2' => '01/01/2025', 'bagian_raw' => 'Staf Pembaca Meter', 'masa_kerja_d2' => '0.5', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 30, 'pendidikan_lanjutan' => 'SMA', 'alamat' => 'Lingk. Margahayu RT 16/RW 06 Kel. Cicurug Kec. Majalengka, Kab. Majalengka', 'nik' => null, 'phone' => null],
            ['nama' => 'Bella Mayhendra', 'tempat_lahir' => 'Sbng', 'tanggal_lahir' => '17/05/1990', 'tanggal_masuk_d2' => '01/01/2025', 'bagian_raw' => 'Staf Pembaca Meter', 'masa_kerja_d2' => '0.5', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => 35, 'pendidikan_lanjutan' => 'SMK', 'alamat' => 'Perum Lewilaja Endah Blok Jumat 003/001 Kec.Sindangwangi', 'nik' => null, 'phone' => null],
            ['nama' => 'DIMAS SAPUTRA', 'tempat_lahir' => 'Idm', 'tanggal_lahir' => '22/Jun/1997', 'tanggal_masuk_d2' => '01/09/2021', 'bagian_raw' => 'Pembaca Meter', 'masa_kerja_d2' => '28', 'pendidikan' => 'S1', 'tanggal_perhitungan' => '01/07/2025', 'umur' => null, 'pendidikan_lanjutan' => null, 'alamat' => 'BLOK LAPANG 2, RT/ RW 008/ 003 DS. TENAJAR KEC. KERTASEMAYA KAB INDRAMAYU', 'nik' => null, 'phone' => null],
            ['nama' => 'Muhamad Ihsan Aldarani P', 'tempat_lahir' => 'Mjl', 'tanggal_lahir' => '25/Apr/2001', 'tanggal_masuk_d2' => '11/09/2023', 'bagian_raw' => 'Baca Meter', 'masa_kerja_d2' => '24', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => null, 'pendidikan_lanjutan' => null, 'alamat' => 'JL. Suha Gg Mantri No. 546', 'nik' => null, 'phone' => null],
            ['nama' => 'Muhamad Yogi Priatna', 'tempat_lahir' => 'Mjl', 'tanggal_lahir' => '03/05/1997', 'tanggal_masuk_d2' => '01/03/2024', 'bagian_raw' => 'Pembaca Meter', 'masa_kerja_d2' => '-97', 'pendidikan' => 'SMA', 'tanggal_perhitungan' => '01/07/2025', 'umur' => null, 'pendidikan_lanjutan' => null, 'alamat' => 'Blok Selasa 002/001 Ds Panyingkiran Kec Jatitujuh', 'nik' => '3210150305970021', 'phone' => '083851119666'],
            ['nama' => 'M Farhan Maulana', 'tempat_lahir' => 'Mjl', 'tanggal_lahir' => '16/01/2004', 'tanggal_masuk_d2' => '01/06/2024', 'bagian_raw' => 'Pembaca Meter', 'masa_kerja_d2' => '-104', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => null, 'pendidikan_lanjutan' => null, 'alamat' => 'Blok Mekarsari 04/02 Sukasari Kidul Argapura', 'nik' => '3210051601040001', 'phone' => '0881022216418'],
            ['nama' => 'Aceng Rahman', 'tempat_lahir' => 'Mjl', 'tanggal_lahir' => '02/08/1999', 'tanggal_masuk_d2' => '01/09/2024', 'bagian_raw' => 'Staf Operator', 'masa_kerja_d2' => '-100', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => null, 'pendidikan_lanjutan' => null, 'alamat' => 'Blok Cengal RT/RW 03/01 Desa Cengal Kec. Maja', 'nik' => '3210060208990061', 'phone' => '08886365019'],
            ['nama' => 'Faizal Dimas Erawan', 'tempat_lahir' => 'Mjl', 'tanggal_lahir' => '28/07/2003', 'tanggal_masuk_d2' => '01/09/2024', 'bagian_raw' => 'Pembaca Meter', 'masa_kerja_d2' => '-104', 'pendidikan' => 'SMK', 'tanggal_perhitungan' => '01/07/2025', 'umur' => null, 'pendidikan_lanjutan' => null, 'alamat' => 'Jl. Margatapa 238/52 RT/RW 002/002 Majalengka Wetan', 'nik' => '3210072807030001', 'phone' => '085187291713'],
            ['nama' => 'Tiara Kasih Hanurandi', 'tempat_lahir' => 'Cms', 'tanggal_lahir' => '31/12/2000', 'tanggal_masuk_d2' => '01/09/2024', 'bagian_raw' => 'Staf Adm', 'masa_kerja_d2' => '-101', 'pendidikan' => 'SI AKUNTANSI', 'tanggal_perhitungan' => '01/07/2025', 'umur' => null, 'pendidikan_lanjutan' => null, 'alamat' => 'Dsn Pasar Salasa RT/RT 03/03 Ds Cikoneng Kec Cikoneng Kab. Ciamis', 'nik' => '3207027112000002', 'phone' => '085721504132'],
            ['nama' => 'Kurniawan', 'tempat_lahir' => 'Mjl', 'tanggal_lahir' => '11/04/1993', 'tanggal_masuk_d2' => '01/09/2024', 'bagian_raw' => 'Office Boy', 'masa_kerja_d2' => '-93', 'pendidikan' => 'MA IPS', 'tanggal_perhitungan' => '01/07/2025', 'umur' => null, 'pendidikan_lanjutan' => null, 'alamat' => 'Link. Pusapa RT/RW 16/4 Ds. Cigasong Kec. Cigasong', 'nik' => '3210201104930001', 'phone' => '089508019393'],
            ['nama' => 'Novan Hasan Hariri', 'tempat_lahir' => 'Mjl', 'tanggal_lahir' => '27/11/2005', 'tanggal_masuk_d2' => '01/10/2024', 'bagian_raw' => 'Staf Operator', 'masa_kerja_d2' => '-106', 'pendidikan' => 'SMA', 'tanggal_perhitungan' => '01/07/2025', 'umur' => null, 'pendidikan_lanjutan' => null, 'alamat' => 'Link. Sukawera 014/005 Ds Tarikolot Kec Majalengka', 'nik' => '3210072711050041', 'phone' => '08817703233'],
        ];

        // Array untuk menyimpan data yang sudah digabungkan dan dinormalisasi
        $mergedData = [];

        // Fungsi helper untuk menormalisasi nama
        $normalizeName = function ($name) {
            $name = strtoupper($name); // Uppercase
            $name = preg_replace('/\s+/', ' ', $name); // Replace multiple spaces with single
            $name = trim($name); // Trim whitespace
            $name = str_replace([',', '.', 'AMD', 'P.'], '', $name); // Remove common punctuation and titles/initials
            $name = str_replace('MUHAMAD', 'MUHAMAD', $name); // Standardize Muhamad/M.
            $name = str_replace('M ', 'MUHAMAD ', $name); // Standardize M. to Muhamad
            $name = str_replace('DEDE TAUFIQ HIDAYAT', 'DEDE TAUFIQ', $name); // Standardize Dede Taufiq
            $name = str_replace('M RAFI SETIAWAN', 'MUHAMAD RAFI SETIAWAN', $name); // Standardize M. Rafi Setiawan
            $name = str_replace('M ALDI FARHAN', 'MUHAMAD ALDI FARHAN PERMADI', $name); // Standardize M. Aldi Farhan P.
            return $name;
        };

        // Fungsi helper untuk mengklasifikasikan gender berdasarkan nama
        $determineGender = function ($name) {
            $normalizedName = strtoupper($name);
            $maleKeywords = [
                'MUHAMAD', 'DADANG', 'DIDIEN', 'IING', 'IRFAN', 'LILI', 'ODIN', 'MEMED',
                'SLAMET', 'DIKY', 'NUKI', 'ARIF', 'DEDE', 'JALALUDIN', 'REZA', 'ALDZIKRILLA',
                'AHMED', 'BAMBANG', 'DIMAS', 'IHSAN', 'YOGI', 'ACENG', 'FAIZAL', 'KURNIAWAN', 'NOVAN'
            ];
            $femaleKeywords = [
                'NOK', 'RIA', 'SITI', 'TIARA', 'BELLA'
            ];

            $isMale = false;
            foreach ($maleKeywords as $keyword) {
                if (str_contains($normalizedName, $keyword)) {
                    $isMale = true;
                    break;
                }
            }

            $isFemale = false;
            foreach ($femaleKeywords as $keyword) {
                if (str_contains($normalizedName, $keyword)) {
                    $isFemale = true;
                    break;
                }
            }

            if ($isMale && !$isFemale) {
                return 'Male';
            } elseif ($isFemale && !$isMale) {
                return 'Female';
            } else {
                return null; // Tidak dapat diklasifikasikan atau ambigu
            }
        };


        // Proses Data Pertama
        foreach ($data1 as $item) {
            $normalizedName = $normalizeName($item['nama']);
            $mergedData[$normalizedName] = [
                'nama' => $item['nama'],
                'keluarga_status' => ($item['keluarga_status_raw'] == 'K') ? 'Menikah' : 'Belum Menikah',
                'jabatan' => 'Kontrak', // Selalu 'Kontrak' untuk karyawan kontrak
                'bagian' => $item['bagian_raw'], // Data 'jabatan' dari D1 sekarang masuk ke 'bagian'
                'unit_kerja' => $item['unit_kerja'],
                'masa_kerja' => $item['masa_kerja_d1'], // From D1 (string now)
                'gaji' => str_replace('.', '', $item['gaji']),
                // Kolom-kolom berikut akan diatur menjadi null secara default jika tidak ada data dari D1
                'tanggal_lahir' => null,
                'tanggal_perhitungan' => null,
                'pendidikan' => null,
                'nomor_induk' => null,
                'gender' => $determineGender($item['nama']), // Klasifikasi gender berdasarkan nama
                'keluarga_anak' => null,
                'status' => null,
            ];

            // Parse and add tanggal_lahir from D1 if available
            if (!empty($item['tanggal_lahir_d1'])) {
                try {
                    // Handle different date formats (DD/MM/YYYY or DD-MM-YYYY)
                    if (strpos($item['tanggal_lahir_d1'], '/') !== false) {
                        $date = Carbon::createFromFormat('d/m/Y', $item['tanggal_lahir_d1']);
                    } else {
                        $date = Carbon::createFromFormat('d-m-Y', $item['tanggal_lahir_d1']);
                    }
                    $mergedData[$normalizedName]['tanggal_lahir'] = $date->format('Y-m-d');
                } catch (\Exception $e) {
                    $mergedData[$normalizedName]['tanggal_lahir'] = null;
                }
            }
        }

        // Proses Data Kedua dan Gabungkan
        foreach ($data2 as $item) {
            $normalizedName = $normalizeName($item['nama']);

            // Jika nama sudah ada, gabungkan data
            if (isset($mergedData[$normalizedName])) {
                // Prioritaskan data dari Dataset 2 jika lebih lengkap atau lebih baru
                $mergedData[$normalizedName]['bagian'] = $item['bagian_raw'] ?? $mergedData[$normalizedName]['bagian']; // Data 'jabatan' dari D2 sekarang masuk ke 'bagian'
                $mergedData[$normalizedName]['unit_kerja'] = $item['unit_kerja'] ?? $mergedData[$normalizedName]['unit_kerja']; // Unit kerja tidak ada di D2, jadi akan tetap dari D1 atau null
                $mergedData[$normalizedName]['masa_kerja'] = $item['masa_kerja_d2'] ?? $mergedData[$normalizedName]['masa_kerja']; // From D2 (string now)
                $mergedData[$normalizedName]['pendidikan'] = $item['pendidikan'] ?? $mergedData[$normalizedName]['pendidikan'];
                $mergedData[$normalizedName]['gender'] = $determineGender($item['nama']) ?? $mergedData[$normalizedName]['gender']; // Prioritaskan D2, jika tidak ada, gunakan yang sudah ada

                // Tanggal Lahir: Prioritaskan D2 jika ada
                if (!empty($item['tanggal_lahir'])) {
                    try {
                        $date = Carbon::parse($item['tanggal_lahir']); // Carbon can parse various formats
                        $mergedData[$normalizedName]['tanggal_lahir'] = $date->format('Y-m-d');
                    } catch (\Exception $e) {
                        // Keep existing or set to null if parsing fails
                        if (!isset($mergedData[$normalizedName]['tanggal_lahir'])) {
                             $mergedData[$normalizedName]['tanggal_lahir'] = null;
                        }
                    }
                }

                // Tanggal Perhitungan: Gunakan dari D2
                if (!empty($item['tanggal_perhitungan'])) {
                    try {
                        $date = Carbon::parse($item['tanggal_perhitungan']);
                        $mergedData[$normalizedName]['tanggal_perhitungan'] = $date->format('Y-m-d');
                    } catch (\Exception $e) {
                        $mergedData[$normalizedName]['tanggal_perhitungan'] = null;
                    }
                }

                // NIK/Nomor Induk dari D2
                $mergedData[$normalizedName]['nomor_induk'] = $item['nik'] ?? $mergedData[$normalizedName]['nomor_induk'];

            } else {
                // Jika nama belum ada, tambahkan sebagai entri baru
                $mergedData[$normalizedName] = [
                    'nama' => $item['nama'],
                    'jabatan' => 'Kontrak', // Selalu 'Kontrak' untuk karyawan kontrak
                    'bagian' => $item['bagian_raw'] ?? null, // Data 'jabatan' dari D2 sekarang masuk ke 'bagian'
                    'unit_kerja' => $item['unit_kerja'] ?? null,
                    'masa_kerja' => $item['masa_kerja_d2'] ?? null, // From D2 (string now)
                    'pendidikan' => $item['pendidikan'] ?? null,
                    'nomor_induk' => $item['nik'] ?? null,
                    'gaji' => null, // Dikosongkan
                    'keluarga_status' => null, // Dikosongkan
                    'keluarga_anak' => null, // Dikosongkan
                    'gender' => $determineGender($item['nama']), // Klasifikasi gender berdasarkan nama
                    'status' => null, // Dikosongkan
                ];

                // Tanggal Lahir dari D2
                if (!empty($item['tanggal_lahir'])) {
                    try {
                        $date = Carbon::parse($item['tanggal_lahir']);
                        $mergedData[$normalizedName]['tanggal_lahir'] = $date->format('Y-m-d');
                    } catch (\Exception $e) {
                        $mergedData[$normalizedName]['tanggal_lahir'] = null;
                    }
                } else {
                    $mergedData[$normalizedName]['tanggal_lahir'] = null;
                }

                // Tanggal Perhitungan dari D2
                if (!empty($item['tanggal_perhitungan'])) {
                    try {
                        $date = Carbon::parse($item['tanggal_perhitungan']);
                        $mergedData[$normalizedName]['tanggal_perhitungan'] = $date->format('Y-m-d');
                    } catch (\Exception $e) {
                        $mergedData[$normalizedName]['tanggal_perhitungan'] = null;
                    }
                } else {
                    $mergedData[$normalizedName]['tanggal_perhitungan'] = null;
                }
            }
        }

        // Insert merged data into the database
        foreach ($mergedData as $data) {
            // Ensure 'gaji' is numeric or null
            $gaji = isset($data['gaji']) ? (float)str_replace('.', '', $data['gaji']) : null;

            KaryawanKontrak::create([
                'nama' => $data['nama'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'gender' => $data['gender'],
                'nomor_induk' => $data['nomor_induk'],
                'jabatan' => $data['jabatan'], // Akan selalu 'Kontrak'
                'bagian' => $data['bagian'],
                'unit_kerja' => $data['unit_kerja'],
                'pendidikan' => $data['pendidikan'],
                'keluarga_status' => $data['keluarga_status'],
                'keluarga_anak' => $data['keluarga_anak'],
                'masa_kerja' => $data['masa_kerja'], // String sekarang
                'tanggal_perhitungan' => $data['tanggal_perhitungan'],
                'gaji' => $gaji,
                'status' => $data['status'],
            ]);
        }
    }
}
