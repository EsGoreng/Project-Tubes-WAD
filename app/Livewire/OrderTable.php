<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Service;
use Livewire\Component;
use App\Models\Customer;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\ExportAction;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\Auth\Guard;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use App\Filament\Exports\OrderExporter;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;

class OrderTable extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->heading('CRUD Order')
            ->description('Tabel ini berfungsi untuk manage order laundry dari pelanggan')
            ->query(Order::query()
                ->with(['customer', 'user'])
                ->withCount('orderDetails'))
            ->columns([
                TextColumn::make('id_orders')
                    ->label('ID')
                    ->searchable(),

                TextColumn::make('customer.nama_lengkap')
                    ->label('Pelanggan')
                    ->searchable()
                    ->description(fn(Order $record) => $record->customer->no_wa ?? '-'),

                TextColumn::make('tgl_masuk')
                    ->label('Tgl Masuk')
                    ->date('d M Y'),

                TextColumn::make('tgl_selesai_estimasi')
                    ->label('Estimasi')
                    ->date('d M Y')
                    ->color(fn($state) => $state < now() && $state != null ? 'danger' : 'success'),

                TextColumn::make('orderDetails_count')
                    ->getStateUsing(fn(Order $record) => $record->order_details_count)
                    ->label('Item')
                    ->alignCenter(),

                TextColumn::make('total_harga')
                    ->money('IDR')
                    ->weight('bold'),

                TextColumn::make('status_pembayaran')
                    ->label('Status Bayar')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'Pending' => 'warning',
                        default => 'gray',
                    }),

                ToggleColumn::make('is_pickup')
                    ->label('Pickup')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                SelectFilter::make('status_pembayaran')
                    ->options([
                        'Pending' => 'Pending',
                        'Lunas' => 'Lunas',
                    ]),

                TernaryFilter::make('is_pickup')
                    ->label('Status Pickup'),

                Filter::make('tgl_masuk')
                    ->schema([
                        DatePicker::make('dari_tanggal'),
                        DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn(Builder $query, $date) => $query->whereDate('tgl_masuk', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date) => $query->whereDate('tgl_masuk', '<=', $date),
                            );
                    })
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->color('success')
                    ->icon('heroicon-o-eye')
                    ->record(fn(Order $record) => $record)
                    ->modalHeading('Detail Order')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->schema(fn(Schema $schema) => $this->getOrderForm($schema))
                    ->mountUsing(function (Schema $schema, Order $record) {
                        $schema->fill(
                            $record->load('orderDetails')->toArray()
                        );
                    })
                    ->disabledSchema(),

                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->record(fn(Order $record) => $record)
                    ->mountUsing(function (Schema $schema, Order $record) {
                        $schema->fill(
                            $record->load('orderDetails')->toArray()
                        );
                    })
                    ->schema(fn(Schema $schema) => $this->getOrderForm($schema))
                    ->action(function (array $data, Order $record) {
                        DB::transaction(function () use ($data, $record) {
                            $record->update([
                                'customer_id'           => $data['customer_id'],
                                'status_pembayaran'     => $data['status_pembayaran'],
                                'tgl_masuk'             => $data['tgl_masuk'],
                                'tgl_selesai_estimasi'  => $data['tgl_selesai_estimasi'],
                                'is_pickup'             => $data['is_pickup'],
                                'total_harga'           => $data['total_harga'],
                            ]);

                            // Hapus detail lama agar tidak duplikat
                            $record->orderDetails()->delete();

                            foreach (data_get($data, 'orderDetails', []) as $detail) {
                                $record->orderDetails()->create($detail);
                            }
                        });

                        Notification::make()
                            ->title('Order berhasil diupdate')
                            ->body('Update ke Order berhasil disimpan.')
                            ->success()
                            ->send();
                    }),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->record(fn(Order $record) => $record)
                    ->action(function (Order $record) {
                        $record->orderDetails()->delete();
                        $record->delete();

                        Notification::make()
                            ->title('Order berhasil dihapus')
                            ->danger()
                            ->send();
                    }),
            ])
            // File: App\Livewire\OrderTable.php

            // ... (kode sebelumnya tetap sama)

            ->headerActions([
                Action::make('add')
                    ->label('Buat Order')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->schema(fn(Schema $schema) => $this->getOrderForm($schema))
                    ->action(function (array $data) {
                        DB::transaction(function () use ($data) {

                            $order = Order::create([
                                'customer_id'           => $data['customer_id'],
                                'user_id'               => auth()->id(),
                                'tgl_masuk'             => $data['tgl_masuk'],
                                'tgl_selesai_estimasi'  => $data['tgl_selesai_estimasi'],
                                'status_pembayaran'     => $data['status_pembayaran'],
                                'is_pickup'             => $data['is_pickup'],
                                'total_harga'           => $data['total_harga'],
                            ]);

                            $items = data_get($data, 'orderDetails', []);

                            foreach ($items as $item) {
                                $order->orderDetails()->create([
                                    'service_id'     => $item['service_id'],
                                    'qty'            => $item['qty'],
                                    'harga_saat_ini' => $item['harga_saat_ini'],
                                    'subtotal'       => $item['subtotal'],
                                ]);
                            }
                        });

                        Notification::make()
                            ->title('Order berhasil disimpan')
                            ->body('Order baru berhasil disimpan.')
                            ->success()
                            ->send();
                    }),

                ExportAction::make('export')
                    ->label('Export')
                    ->icon('heroicon-o-document-arrow-down')
                    ->exporter(OrderExporter::class)
            ]);

    }

    public function getOrderForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Utama')
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'nama_lengkap')
                            ->required()
                            ->label('Pelanggan')
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $customer = Customer::find($state);

                                if ($customer) {
                                    $set('customer.alamat', $customer->alamat);
                                    $set('customer.no_wa', $customer->no_wa);
                                    $set('customer.email', $customer->email);
                                } else {
                                    $set('customer.alamat', null);
                                    $set('customer.no_wa', null);
                                    $set('customer.email', null);
                                }
                            }),

                        Select::make('status_pembayaran')
                            ->options([
                                'Pending' => 'Pending',
                                'Lunas' => 'Lunas',
                            ])
                            ->required()
                            ->default('Pending'),

                        Toggle::make('is_pickup')
                            ->label('Perlu Dijemput?')
                            ->inline(false),

                        DatePicker::make('tgl_masuk')
                            ->required()
                            ->default(now()),

                        DatePicker::make('tgl_selesai_estimasi')
                            ->default(now()->addDays(2)),

                        

                        TextInput::make('total_harga')
                            ->label('Total Tagihan')
                            ->prefix('Rp')
                            ->numeric()
                            ->readOnly()
                            ->reactive()
                            ->live()
                            ->dehydrated(),

                        Textarea::make('customer.alamat')
                            ->label('Alamat')
                            ->rows(10)
                            ->disabled()
                            ->readonly()
                            ->columns(150),

                        TextInput::make('customer.no_wa')
                            ->label('No WA')
                            ->disabled()
                            ->readOnly()
                            ->copyable('1'),

                        TextInput::make('customer.email')
                            ->label('Email')
                            ->disabled()
                            ->readOnly()
                            ->copyable('1')
                    ])->columns(3),

                Section::make('Detail Laundry')
                    ->schema([
                        Repeater::make('orderDetails')
                            ->relationship()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            })
                            ->schema([
                                Select::make('service_id')
                                    ->label('Layanan')
                                    ->relationship(
                                        name: 'service',
                                        titleAttribute: 'nama_paket',
                                        modifyQueryUsing: fn($query) =>
                                        $query->where('is_active', true)
                                    )
                                    ->required()
                                    ->live()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $service = Service::find($state);

                                        if (! $service) {
                                            return;
                                        }

                                        $set('harga_saat_ini', $service->harga);

                                        $details = $get('../../orderDetails');
                                        $total = collect($details)->sum(fn($item) => $item['subtotal'] ?? 0);
                                        $set('../../total_harga', $total);
                                    }),


                                TextInput::make('qty')
                                    ->label('Qty (Kg/Pcs)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->live()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $harga = $get('harga_saat_ini') ?? 0;

                                        $subtotal = $state * $harga;
                                        $set('subtotal', $subtotal);

                                        $details = $get('../../orderDetails');
                                        $total = collect($details)->sum(fn($item) => $item['subtotal'] ?? 0);
                                        $set('../../total_harga', $total);
                                    }),


                                TextInput::make('harga_saat_ini')
                                    ->label('Harga/Unit')
                                    ->numeric()
                                    ->reactive()
                                    ->readOnly()
                                    ->dehydrated()
                                    ->prefix('Rp'),

                                TextInput::make('subtotal')
                                    ->numeric()
                                    ->reactive()
                                    ->readOnly()
                                    ->dehydrated()
                                    ->prefix('Rp'),
                            ])
                            ->columns(4)
                            ->addActionLabel('Tambah Item')
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            }),
                    ])
            ]);
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        $details = collect($get('orderDetails'));

        $total = $details->sum(function ($item) {
            return (float) ($item['subtotal'] ?? 0);
        });

        $set('total_harga', $total);
    }

    public function render()
    {
        return view('livewire.order-table');
    }
}
