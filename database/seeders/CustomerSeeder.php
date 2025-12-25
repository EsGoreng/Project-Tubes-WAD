<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            Customer::create([
                'nama_lengkap' => $faker->name,
                'no_wa' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('12345678'),
                'description' => 'Pelanggan setia',
            ]);
        }

        Customer::create([
            'nama_lengkap' => 'Udin',
            'no_wa' => '081234567890',
            'alamat' => 'Jl. Contoh No. 123',
            'email' => 'uramazingdev@gmail.com',
            'password' => Hash::make('pelanggan123'),
            'description' => 'Pelanggan',
        ]);
    }
}
