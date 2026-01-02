<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Service;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\OrderDetail;
use Filament\Actions\Action;
use App\Models\OrderTracking;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

use Filament\Actions\Contracts\HasActions;

class OrderCreate extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $selectedServices = [];
    public $quantities = [];
    
    
    public $checkout_is_pickup = '0';
    public $checkout_alamat = '';
    public $checkout_catatan = '';

    public function table(Table $table): Table
    {
        return $table
            ->query(Service::query()->where('is_active', true))
            ->columns([
                TextColumn::make('nama_paket')
                    ->label('Nama Paket')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('sm'),
                
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->wrap()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->deskripsi),
                
                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
                
                TextColumn::make('satuan')
                    ->label('Satuan')
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('estimasi_durasi')
                    ->label('Estimasi')
                    ->formatStateUsing(fn ($state) => $state . ' Jam')
                    ->badge()
                    ->color('warning'),
            ])
            ->actions([
                Action::make('add_to_order')
                    ->label('Tambah')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->form([
                        TextInput::make('qty')
                            ->label('Jumlah')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->suffix(fn ($record) => $record->satuan)
                            ->required()
                            ->live()
                            ->helperText(fn ($record, $get) => 
                                'Subtotal: Rp ' . number_format(($get('qty') ?? 1) * $record->harga, 0, ',', '.')
                            ),
                    ])
                    ->action(function (Service $record, array $data) {
                        $this->addToCart($record->id_services, $data['qty']);
                    })
                    ->modalHeading(fn ($record) => 'Tambah ' . $record->nama_paket)
                    ->modalSubmitActionLabel('Tambah ke Keranjang')
                    ->modalWidth('md'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Tidak ada layanan tersedia')
            ->emptyStateDescription('Saat ini tidak ada paket layanan yang aktif')
            ->emptyStateIcon('heroicon-o-inbox');
    }

    public function addToCart($serviceId, $qty)
    {
        if (isset($this->selectedServices[$serviceId])) {
            
            $this->quantities[$serviceId] += $qty;
        } else {
            
            $this->selectedServices[$serviceId] = Service::find($serviceId);
            $this->quantities[$serviceId] = $qty;
        }

        Notification::make()
            ->title('Ditambahkan ke keranjang!')
            ->success()
            ->send();
    }

    public function removeFromCart($serviceId)
    {
        unset($this->selectedServices[$serviceId]);
        unset($this->quantities[$serviceId]);

        Notification::make()
            ->title('Item dihapus dari keranjang')
            ->warning()
            ->send();
    }

    public function updateQuantity($serviceId, $qty)
    {
        if ($qty <= 0) {
            $this->removeFromCart($serviceId);
            return;
        }
        $this->quantities[$serviceId] = $qty;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->selectedServices as $serviceId => $service) {
            $total += $service->harga * $this->quantities[$serviceId];
        }
        return $total;
    }

    public function createOrder()
    {
        if (empty($this->selectedServices)) {
            Notification::make()
                ->title('Keranjang Kosong!')
                ->body('Silakan pilih minimal satu layanan terlebih dahulu.')
                ->warning()
                ->send();
            return;
        }

        $this->dispatch('open-checkout-modal');
    }

    public function processOrder()
    {
        
        if (empty($this->selectedServices)) {
            Notification::make()
                ->title('Keranjang Kosong!')
                ->body('Silakan pilih minimal satu layanan terlebih dahulu.')
                ->warning()
                ->send();
            return;
        }

        if ($this->checkout_is_pickup === '1' && empty($this->checkout_alamat)) {
            Notification::make()
                ->title('Alamat Diperlukan!')
                ->body('Silakan masukkan alamat penjemputan.')
                ->warning()
                ->send();
            return;
        }

        try {
            
            $maxDuration = 0;
            foreach ($this->selectedServices as $service) {
                if ($service->estimasi_durasi > $maxDuration) {
                    $maxDuration = $service->estimasi_durasi;
                }
            }

            
            $order = Order::create([
                'customer_id' => Auth::guard('customer')->id(),
                'tgl_masuk' => now(),
                'tgl_selesai_estimasi' => now()->addDays($maxDuration),
                'total_harga' => $this->getTotal(),
                'status_pembayaran' => 'Pending',
                'is_pickup' => $this->checkout_is_pickup === '1',
                'catatan' => $this->checkout_catatan,
            ]);

            
            foreach ($this->selectedServices as $serviceId => $service) {
                OrderDetail::create([
                    'order_id' => $order->id_orders,
                    'service_id' => $serviceId,
                    'qty' => $this->quantities[$serviceId],
                    'harga_saat_ini' => $service->harga,
                    'subtotal' => $service->harga * $this->quantities[$serviceId],
                ]);
            }

            
            OrderTracking::create([
                'order_id' => $order->id_orders,
                'status' => $this->checkout_is_pickup === '1' ? 'Perlu Dijemput' : 'Dicuci',
            ]);

            
            $this->selectedServices = [];
            $this->quantities = [];
            $this->checkout_is_pickup = '0';
            $this->checkout_alamat = '';
            $this->checkout_catatan = '';

            Notification::make()
                ->title('Pesanan Berhasil Dibuat!')
                ->body('Nomor order Anda: #' . str_pad($order->id_orders, 5, '0', STR_PAD_LEFT))
                ->success()
                ->duration(5000)
                ->send();

            $this->dispatch('order-created');
            $this->dispatch('close-checkout-modal');

        } catch (\Exception $e) {
            Notification::make()
                ->title('Terjadi Kesalahan!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.order-create');
    }
}