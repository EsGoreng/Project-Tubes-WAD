<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'nama_lengkap' => 'Andi Susilo',
                'no_wa' => '081234567890',
                'alamat' => 'Jl. Mawar No. 10',
                'email' => 'andi@gmail.com',
                'password' => Hash::make('andi123'),
                'created_at' => now(),
            ],
            [
                'nama_lengkap' => 'Bu Dewi',
                'no_wa' => '089876543210',
                'alamat' => 'Perumahan Griya Indah Blok A',
                'email' => null, // Skenario pelanggan offline tanpa email
                'password' => null,
                'created_at' => now(),
            ],
            [
                'nama_lengkap' => 'Doni (Mahasiswa)',
                'no_wa' => '085555555555',
                'alamat' => 'Kos Pak Haji, Kamar 3',
                'email' => 'doni@kampus.ac.id',
                'password' => Hash::make('doni123'),
                'created_at' => now(),
            ]
        ]);
    }
}
