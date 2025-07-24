<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
        {
            $data = [
                [
                    'nama' => 'Rina Kartika',
                    'tanggal_lahir' => '1990-05-12',
                    'gender' => 'female',
                    'kontak' => '081234567890',
                    'email' => 'rina.kartika@example.com',
                    'alamat' => 'Jl. Anggrek No. 15, Majalengka',
                    'tanggal_masuk' => '2015-04-10',
                    'masa_kerja' => '9',
                    'golongan' => 'c2',
                    'gaji' => 4500000,
                    'status' => 'aktif',
                ],
                [
                    'nama' => 'Dedi Permana',
                    'tanggal_lahir' => '1985-08-23',
                    'gender' => 'male',
                    'kontak' => '082233445566',
                    'email' => 'dedi.permana@example.com',
                    'alamat' => 'Jl. Melati No. 21, Panyingkiran',
                    'tanggal_masuk' => '2010-07-01',
                    'masa_kerja' => '14',
                    'golongan' => 'd1',
                    'gaji' => 7000000,
                    'status' => 'aktif',
                ],
                [
                    'nama' => 'Siti Aminah',
                    'tanggal_lahir' => '1995-01-05',
                    'gender' => 'female',
                    'kontak' => '083144556677',
                    'email' => 'siti.aminah@example.com',
                    'alamat' => 'Jl. Kenanga No. 8, Cigasong',
                    'tanggal_masuk' => '2020-02-20',
                    'masa_kerja' => '4',
                    'golongan' => 'b2',
                    'gaji' => 3500000,
                    'status' => 'aktif',
                ],
                [
                    'nama' => 'Ahmad Fauzi',
                    'tanggal_lahir' => '1992-11-30',
                    'gender' => 'male',
                    'kontak' => '081255667788',
                    'email' => 'ahmad.fauzi@example.com',
                    'alamat' => 'Jl. Merdeka No. 33, Kadipaten',
                    'tanggal_masuk' => '2018-09-10',
                    'masa_kerja' => '6',
                    'golongan' => 'c1',
                    'gaji' => 5000000,
                    'status' => 'aktif',
                ],
                [
                    'nama' => 'Lilis Suryani',
                    'tanggal_lahir' => '1988-03-17',
                    'gender' => 'female',
                    'kontak' => '082198765432',
                    'email' => 'lilis.suryani@example.com',
                    'alamat' => 'Jl. Cendrawasih No. 10, Rajagaluh',
                    'tanggal_masuk' => '2012-06-01',
                    'masa_kerja' => '12',
                    'golongan' => 'c3',
                    'gaji' => 6200000,
                    'status' => 'aktif',
                ],
            ];

            foreach ($data as $pegawai) {
                Pegawai::create($pegawai);
            }
        }
}
