<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Order 1: Andi, Cuci Komplit, Masih Proses
        DB::table('orders')->insert([
            'id_orders' => 1, // Kita set ID manual agar mudah direlasikan di child table
            'customer_id' => 1, // Andi
            'user_id' => 2, // Kasir Hadi
            'tgl_masuk' => now(),
            'tgl_selesai_estimasi' => now()->addDays(2),
            'total_harga' => 21000.00, // Misal 3kg * 7000
            'status_pembayaran' => 'Pending',
            'is_pickup' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Order 2: Bu Dewi, Kilat, Sudah Lunas
        DB::table('orders')->insert([
            'id_orders' => 2,
            'customer_id' => 2, // Bu Dewi
            'user_id' => 2, // Kasir Hadi
            'tgl_masuk' => now()->subHours(6), // Masuk 6 jam lalu
            'tgl_selesai_estimasi' => now()->subHour(), // Sudah lewat estimasi
            'total_harga' => 24000.00, // 2kg * 12000
            'status_pembayaran' => 'Lunas',
            'is_pickup' => true, // Minta dijemput
            'created_at' => now()->subHours(6),
            'updated_at' => now(),
        ]);
    }
}
