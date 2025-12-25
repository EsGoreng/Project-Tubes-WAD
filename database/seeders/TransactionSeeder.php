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
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
                'tgl_masuk' => Carbon::now()->subDays(rand(0, 5)), // Tanggal acak 0-5 hari lalu
                'tgl_selesai_estimasi' => Carbon::now()->addDays(2),
                'status_pembayaran' => rand(0, 1) ? 'Lunas' : 'Pending',
                'is_pickup' => rand(0, 1),
                'total_harga' => 0, // Nanti diupdate
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

            // 5. Buat Tracking Awal
            OrderTracking::create([
                'order_id' => $order->id_orders,
                'status' => 'Dicuci',
                'created_at' => $order->tgl_masuk,
                'updated_at' => $order->tgl_masuk
            ]);
        }
    }
}
