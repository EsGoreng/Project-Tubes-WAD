<?php

namespace App\Livewire;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;

class CustomerStatsChart extends ChartWidget
{
    protected ?string $heading = 'Segmentasi Pelanggan';
    
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Pelanggan Baru (0 transaksi)
        $baru = Customer::withCount('orders')
            ->having('orders_count', '=', 0)
            ->count();

        // Pelanggan Aktif (1-3 transaksi)
        $aktif = Customer::withCount('orders')
            ->having('orders_count', '>=', 1)
            ->having('orders_count', '<=', 3)
            ->count();

        // Pelanggan Setia (>3 transaksi)
        $setia = Customer::withCount('orders')
            ->having('orders_count', '>', 3)
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pelanggan',
                    'data' => [$baru, $aktif, $setia],
                    'backgroundColor' => [
                        'rgb(156, 163, 175)',  // Baru - Gray
                        'rgb(251, 191, 36)',   // Aktif - Yellow
                        'rgb(34, 197, 94)',    // Setia - Green
                    ],
                ],
            ],
            'labels' => ['Pelanggan Baru', 'Pelanggan Aktif', 'Pelanggan Setia'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => 'function(context) { 
                            let label = context.label || "";
                            let value = context.parsed || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            return label + ": " + value + " (" + percentage + "%)";
                        }',
                    ],
                ],
            ],
        ];
    }
}