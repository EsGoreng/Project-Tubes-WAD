<?php

namespace Database\Seeders;

use App\Models\Service;
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
        $services = [
            [
                'nama_paket' => 'Cuci Komplit Regular',
                'deskripsi' => 'cuci baju (3 Hari) aowkaokw',
                'satuan' => 'Kg',
                'harga' => 7000,
                'estimasi_durasi' => 72, // jam
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Komplit Express',
                'deskripsi' => 'cuci baju  (1 Hari) aowkaokw',
                'satuan' => 'Kg',
                'harga' => 12000,
                'estimasi_durasi' => 24,
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Kering Setrika',
                'deskripsi' => 'cuci baju aowkaokw',
                'satuan' => 'Kg',
                'harga' => 6000,
                'estimasi_durasi' => 48,
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Bedcover Besar',
                'deskripsi' => 'cuci bedcover aowkaokw',
                'satuan' => 'Pcs',
                'harga' => 25000,
                'estimasi_durasi' => 72,
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Karpet',
                'deskripsi' => 'cuci karpet (Per Meter) aowkaokw',
                'satuan' => 'Pcs',
                'harga' => 15000,
                'estimasi_durasi' => 96,
                'is_active' => true
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
