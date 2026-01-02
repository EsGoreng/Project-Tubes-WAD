<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use App\Models\OrderDetail;
use App\Models\OrderTracking;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data pendukung
        $customers = Customer::all();
        $users = User::where('role', 'kasir')->get(); // Transaksi biasanya oleh kasir
        $services = Service::all();
        $faker = Faker::create('id_ID');

        if ($customers->isEmpty() || $users->isEmpty() || $services->isEmpty()) {
            $this->command->info('Lewati TransactionSeeder karena data User/Customer/Service kosong.');
            return;
        }

        // Buat 15 Transaksi Dummy
        for ($i = 0; $i < 15; $i++) {

            // 1. Pilih Customer & Kasir Random
            $randomCustomer = $customers->random();
            $randomUser = $users->random();

            // 2. Buat Kerangka Order
            $order = Order::create([
                'customer_id' => $randomCustomer->id_customer,
                'user_id' => $randomUser->id_user,
                'tgl_masuk' => Carbon::now()->subDays(rand(0, 5)),
                'tgl_selesai_estimasi' => Carbon::now()->addDays(2),
                'status_pembayaran' => rand(0, 1) ? 'Lunas' : 'Pending',
                'catatan' => $faker->paragraph(2),
                'alamat_jemput' => $faker->address,
                'is_pickup' => rand(0, 1),
                'total_harga' => 0,
            ]);

            // 3. Isi Detail Order (Item laundry)
            $totalHargaTransaksi = 0;
            // Setiap order bisa punya 1 sampai 3 item
            $jumlahItem = rand(1, 3);

            for ($j = 0; $j < $jumlahItem; $j++) {
                $randomService = $services->random();
                $qty = rand(1, 5); // Berat 1-5 kg atau 1-5 pcs
                $subtotal = $randomService->harga * $qty;

                OrderDetail::create([
                    'order_id' => $order->id_orders,
                    'service_id' => $randomService->id_services,
                    'qty' => $qty,
                    'harga_saat_ini' => $randomService->harga,
                    'subtotal' => $subtotal
                ]);

                $totalHargaTransaksi += $subtotal;
            }

            // 4. Update Total Harga di Tabel Order
            $order->update(['total_harga' => $totalHargaTransaksi]);

            // 5. Buat Tracking (Simulasi History Status)
            $possibleStatuses = ['Perlu Dijemput', 'Dicuci', 'Dijemur', 'Disetrika', 'Siap'];
            
            // Tentukan sampai tahap mana order ini (Random 0-3)
            $currentStageIndex = rand(0, count($possibleStatuses) - 1);

            for ($k = 0; $k <= $currentStageIndex; $k++) {
                // Buat waktu tracking bertahap (setiap status beda 4 jam)
                $trackingTime = Carbon::parse($order->tgl_masuk)->addHours($k * 4);

                OrderTracking::create([
                    'order_id' => $order->id_orders,
                    'status' => $possibleStatuses[$k],
                    'created_at' => $trackingTime,
                    'updated_at' => $trackingTime
                ]);
            }
        }
    }
}
