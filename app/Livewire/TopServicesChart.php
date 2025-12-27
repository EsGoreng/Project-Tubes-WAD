<?php

namespace App\Livewire;

use App\Models\Service;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopServicesChart extends ChartWidget
{
    protected ?string $heading = 'Layanan Paling Populer';
    
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Ambil 5 layanan paling banyak dipesan
        $topServices = Service::select('services.nama_paket', DB::raw('COUNT(order_details.id_order_details) as total_orders'))
            ->join('order_details', 'services.id_services', '=', 'order_details.service_id')
            ->groupBy('services.id_services', 'services.nama_paket')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();

        $labels = $topServices->pluck('nama_paket')->toArray();
        $data = $topServices->pluck('total_orders')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)',
                        'rgb(251, 191, 36)',
                        'rgb(34, 197, 94)',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}