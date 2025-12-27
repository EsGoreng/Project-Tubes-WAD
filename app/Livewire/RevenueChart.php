<?php

namespace App\Livewire;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Pendapatan (30 Hari Terakhir)';
    
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Ambil data 30 hari terakhir
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            
            $revenue = Order::where('status_pembayaran', 'Lunas')
                ->whereDate('tgl_masuk', $date)
                ->sum('total_harga');
            
            $data[] = $revenue;
            $labels[] = $date->format('d M');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => 'function(context) { return "Rp " + context.parsed.y.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + (value/1000) + "k"; }',
                    ],
                ],
            ],
        ];
    }
}