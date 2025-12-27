<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Pendapatan
        $totalRevenue = Order::where('status_pembayaran', 'Lunas')->sum('total_harga');
        
        // Pendapatan Bulan Ini
        $monthlyRevenue = Order::where('status_pembayaran', 'Lunas')
            ->whereMonth('tgl_masuk', now()->month)
            ->whereYear('tgl_masuk', now()->year)
            ->sum('total_harga');
        
        // Pendapatan Bulan Lalu
        $lastMonthRevenue = Order::where('status_pembayaran', 'Lunas')
            ->whereMonth('tgl_masuk', now()->subMonth()->month)
            ->whereYear('tgl_masuk', now()->subMonth()->year)
            ->sum('total_harga');
        
        // Hitung persentase perubahan
        $revenueChange = $lastMonthRevenue > 0 
            ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 0;
        
        // Total Order
        $totalOrders = Order::count();
        $monthlyOrders = Order::whereMonth('tgl_masuk', now()->month)
            ->whereYear('tgl_masuk', now()->year)
            ->count();
        
        // Total Pelanggan
        $totalCustomers = Customer::count();
        $newCustomersThisMonth = Customer::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Pelanggan Loyal (>5 transaksi)
        $loyalCustomers = Customer::withCount('orders')
            ->having('orders_count', '>', 5)
            ->count();
        
        // Order Pending Pembayaran
        $pendingPayments = Order::where('status_pembayaran', 'Pending')->count();
        $pendingAmount = Order::where('status_pembayaran', 'Pending')->sum('total_harga');
        
        // Layanan Aktif
        $activeServices = Service::where('is_active', true)->count();
        $totalServices = Service::count();

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Pendapatan bulan ini: Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->descriptionIcon($revenueChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueChange >= 0 ? 'success' : 'danger')
                ->chart($this->getRevenueChart())
                ->extraAttributes([
                    'class' => ' ',
                ]),

            Stat::make('Total Order', number_format($totalOrders, 0, ',', '.'))
                ->description($monthlyOrders . ' order bulan ini')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info')
                ->chart($this->getOrderChart())
                ->extraAttributes([
                    'class' => ' ',
                ]),

            Stat::make('Total Pelanggan', number_format($totalCustomers, 0, ',', '.'))
                ->description($newCustomersThisMonth . ' pelanggan baru bulan ini')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->extraAttributes([
                    'class' => ' ',
                ]),

            Stat::make('Pelanggan Loyal', number_format($loyalCustomers, 0, ',', '.'))
                ->description('Pelanggan dengan >5 transaksi')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->extraAttributes([
                    'class' => ' ',
                ]),

            Stat::make('Pending Pembayaran', number_format($pendingPayments, 0, ',', '.') . ' order')
                ->description('Total: Rp ' . number_format($pendingAmount, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger')
                ->extraAttributes([
                    'class' => '',
                ]),

            Stat::make('Layanan Aktif', $activeServices . ' / ' . $totalServices)
                ->description('Total paket layanan tersedia')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('primary')
                ->extraAttributes([
                    'class' => '',
                ]),
        ];
    }

    protected function getRevenueChart(): array
    {
        // Chart pendapatan 7 hari terakhir
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Order::where('status_pembayaran', 'Lunas')
                ->whereDate('tgl_masuk', $date)
                ->sum('total_harga');
            $data[] = $revenue / 1000; // Dalam ribuan untuk chart
        }
        return $data;
    }

    protected function getOrderChart(): array
    {
        // Chart order 7 hari terakhir
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Order::whereDate('tgl_masuk', $date)->count();
            $data[] = $count;
        }
        return $data;
    }
}