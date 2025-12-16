<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_details')->insert([
            [
                'order_id' => 1,
                'service_id' => 1, // Cuci Komplit
                'qty' => 3.0, // 3 Kg
                'harga_saat_ini' => 7000.00,
                'subtotal' => 21000.00,
                'created_at' => now(),
            ]
        ]);

        // Detail untuk Order 2 (Bu Dewi)
        DB::table('order_details')->insert([
            [
                'order_id' => 2,
                'service_id' => 3, // Layanan Kilat
                'qty' => 2.0, // 2 Kg
                'harga_saat_ini' => 12000.00,
                'subtotal' => 24000.00,
                'created_at' => now(),
            ]
        ]);
    }
}
