<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tracking untuk Order 1 (Masih tahap awal)
        DB::table('order_tracking')->insert([
            [
                'order_id' => 1,
                'status' => 'Dicuci',
                'updated_at' => now()->subMinutes(30),
            ]
        ]);

        // Tracking untuk Order 2 (Sudah mau selesai)
        DB::table('order_tracking')->insert([
            [
                'order_id' => 2,
                'status' => 'Dicuci',
                'updated_at' => now()->subHours(5),
            ],
            [
                'order_id' => 2,
                'status' => 'Dijemur', // Kilat mungkin pakai dryer, tapi anggap flow standar
                'updated_at' => now()->subHours(4),
            ],
            [
                'order_id' => 2,
                'status' => 'Disetrika',
                'updated_at' => now()->subHours(2),
            ],
            [
                'order_id' => 2,
                'status' => 'Siap',
                'updated_at' => now()->subMinutes(10),
            ]
        ]);
    }
}
