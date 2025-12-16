<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'nama_paket' => 'Cuci Komplit (Reguler)',
                'satuan' => 'Kg',
                'harga' => 7000.00,
                'estimasi_durasi' => 48, // 2 Hari
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'nama_paket' => 'Cuci Kering + Setrika',
                'satuan' => 'Kg',
                'harga' => 5000.00,
                'estimasi_durasi' => 24, // 1 Hari
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'nama_paket' => 'Layanan Kilat (Express)',
                'satuan' => 'Kg',
                'harga' => 12000.00,
                'estimasi_durasi' => 5, // 5 Jam
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'nama_paket' => 'Bed Cover (Besar)',
                'satuan' => 'Pcs',
                'harga' => 25000.00,
                'estimasi_durasi' => 72, // 3 Hari
                'is_active' => true,
                'created_at' => now(),
            ]
        ]);
    }
}
