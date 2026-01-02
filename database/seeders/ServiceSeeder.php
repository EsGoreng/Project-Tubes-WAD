<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

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
                'deskripsi' => 'Layanan cuci, kering, dan setrika standar. Pengerjaan 3 hari.',
                'satuan' => 'Kg',
                'harga' => 7000,
                'estimasi_durasi' => 72, 
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Komplit Express',
                'deskripsi' => 'Layanan prioritas cuci, kering, dan setrika. Selesai dalam 1 hari.',
                'satuan' => 'Kg',
                'harga' => 12000,
                'estimasi_durasi' => 24, 
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Komplit Kilat',
                'deskripsi' => 'Layanan super cepat, selesai dalam 6 jam (tunggu di tempat/ditunggu).',
                'satuan' => 'Kg',
                'harga' => 18000,
                'estimasi_durasi' => 6, 
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Kering Lipat',
                'deskripsi' => 'Cuci dan kering saja tanpa setrika. Pakaian dilipat rapi.',
                'satuan' => 'Kg',
                'harga' => 5000,
                'estimasi_durasi' => 48, 
                'is_active' => true
            ],
            [
                'nama_paket' => 'Setrika Saja',
                'deskripsi' => 'Hanya jasa setrika dan pewangi pakaian.',
                'satuan' => 'Kg',
                'harga' => 5000,
                'estimasi_durasi' => 24,
                'is_active' => true
            ],

            
            [
                'nama_paket' => 'Cuci Bedcover Kecil/Single',
                'deskripsi' => 'Pencucian khusus bedcover ukuran single.',
                'satuan' => 'Pcs',
                'harga' => 20000,
                'estimasi_durasi' => 72,
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Bedcover Besar/Double',
                'deskripsi' => 'Pencucian khusus bedcover ukuran king/queen.',
                'satuan' => 'Pcs',
                'harga' => 30000,
                'estimasi_durasi' => 72,
                'is_active' => true
            ],

            
            [
                'nama_paket' => 'Cuci Karpet',
                'deskripsi' => 'Deep cleaning untuk karpet (harga per meter persegi).',
                'satuan' => 'm2', 
                'harga' => 15000,
                'estimasi_durasi' => 96, 
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Boneka (Small)',
                'deskripsi' => 'Cuci boneka ukuran kecil (< 30cm).',
                'satuan' => 'Pcs',
                'harga' => 10000,
                'estimasi_durasi' => 48,
                'is_active' => true
            ],
            [
                'nama_paket' => 'Cuci Sepatu Deep Clean',
                'deskripsi' => 'Perawatan detail untuk sepatu (Canvas/Suede/Leather).',
                'satuan' => 'Pasang',
                'harga' => 35000,
                'estimasi_durasi' => 72,
                'is_active' => true
            ],
            [
                'nama_paket' => 'Dry Clean Jas/Suit',
                'deskripsi' => 'Pencucian kering khusus bahan jas formal.',
                'satuan' => 'Set',
                'harga' => 45000,
                'estimasi_durasi' => 72,
                'is_active' => true
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}