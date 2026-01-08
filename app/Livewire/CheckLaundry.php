<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class CheckLaundry extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithActions;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where('customer_id', Auth::guard('customer')->id())
                    ->with(['orderDetails.service', 'latestStatus'])
            )
            ->columns([
                TextColumn::make('id_orders')
                    ->label('No. Order')
                    ->formatStateUsing(fn ($state) => '#' . str_pad($state, 5, '0', STR_PAD_LEFT))
                    ->sortable(),
                    
                TextColumn::make('tgl_masuk')
                    ->label('Tanggal Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                    
                TextColumn::make('is_pickup')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Dijemput' : 'Antar Sendiri')
                    ->color(fn ($state) => $state ? 'warning' : 'success'),
                    
                TextColumn::make('latestStatus.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Perlu Dijemput' => 'warning',
                        'Dicuci' => 'info',
                        'Dijemur' => 'primary',
                        'Disetrika' => 'primary',
                        'Siap' => 'success',
                        default => 'gray',
                    }),
                    
                TextColumn::make('orderDetails.service.nama_paket')
                    ->label('Layanan')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->expandableLimitedList(),
                    
                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('status_pembayaran')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'Pending' => 'warning',
                        default => 'gray',
                    }),
            ]);
    }

    public function render(): View
    {
        return view('livewire.check-laundry');
    }
}