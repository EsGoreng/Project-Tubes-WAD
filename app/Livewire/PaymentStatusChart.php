<?php

namespace App\Livewire;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class PaymentStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Pembayaran';
    
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $lunas = Order::where('status_pembayaran', 'Lunas')->count();
        $pending = Order::where('status_pembayaran', 'Pending')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Order',
                    'data' => [$lunas, $pending],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',    // Lunas - Green
                        'rgb(239, 68, 68)',    // Pending - Red
                    ],
                ],
            ],
            'labels' => ['Lunas', 'Pending'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}