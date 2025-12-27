<?php

namespace App\Livewire;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Laundry';
    
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $statuses = ['Dicuci', 'Dijemur', 'Disetrika', 'Siap'];
        $data = [];
        
        foreach ($statuses as $status) {
            $count = Order::whereHas('latestStatus', function($query) use ($status) {
                $query->where('status', $status);
            })->count();
            
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Order',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',   // Dicuci - Blue
                        'rgb(251, 191, 36)',   // Dijemur - Yellow
                        'rgb(139, 92, 246)',   // Disetrika - Purple
                        'rgb(34, 197, 94)',    // Siap - Green
                    ],
                ],
            ],
            'labels' => $statuses,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
                ],
            ],
        ];
    }
}