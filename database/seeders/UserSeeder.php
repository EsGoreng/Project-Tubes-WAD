<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Intan Nurlistiyani (Owner)',
                'email' => 'admin@laundry.com',
                'password' => Hash::make('admin123'), // Password di-hash
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Hadi (Kasir)',
                'email' => 'hadi@laundry.com',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Akhdan (Kasir)',
                'email' => 'akhdan@laundry.com',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
