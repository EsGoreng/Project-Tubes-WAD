<?php

namespace App\Livewire;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;

use Livewire\Component;

use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;

use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Filament\Notifications\Notification;

use Illuminate\Database\Eloquent\Builder;


use Filament\Actions\Contracts\HasActions;
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
                ->with(['customer', 'user', 'trackings'])
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

                TextColumn::make('latestStatus.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Siap' => 'success',
                        'Disetrika' => 'info',
                        'Dijemur' => 'warning',
                        'Dicuci' => 'primary',
                        default => 'gray',
                    })
                    ->alignCenter(),

                TextColumn::make(name: 'total_harga')
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
                    ->label('Jemput')
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

                SelectFilter::make('latestStatus.status')
                    ->label('Status Laundry')
                    ->options([
                        'Dicuci' => 'Dicuci',
                        'Dijemur' => 'Dijemur',
                        'Disetrika' => 'Disetrika',
                        'Siap' => 'Siap',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $status) => $query->whereHas('latestStatus', fn (Builder $query) => $query->where('status', $status))
                        );
                    }),

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
                        $record->load('orderDetails');
                        $data = $record->toArray();
                        $data['orderDetails'] = $record->orderDetails->toArray();
                        $data['tracking_status'] = $record->latestStatus?->status;
                        $schema->fill($data);
                    })
                    ->disabledSchema(),

                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->record(fn(Order $record) => $record)
                    ->mountUsing(function (Schema $schema, Order $record) {
                        $record->load('orderDetails');
                        $data = $record->toArray();
                        $data['orderDetails'] = $record->orderDetails->toArray();
                        $data['tracking_status'] = $record->latestStatus?->status;
                        $schema->fill($data);
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

                            // Handle Order Details (Update, Create, Delete)
                            $existingIds = $record->orderDetails()->pluck('id_order_details')->toArray();
                            $submittedDetails = data_get($data, 'orderDetails', []);
                            $submittedIds = [];

                            foreach ($submittedDetails as $detail) {
                                $detailId = $detail['id_order_details'] ?? null;

                                if ($detailId && in_array($detailId, $existingIds)) {
                                    // Update existing
                                    $record->orderDetails()->where('id_order_details', $detailId)->update([
                                        'service_id'     => $detail['service_id'],
                                        'qty'            => $detail['qty'],
                                        'harga_saat_ini' => $detail['harga_saat_ini'],
                                        'subtotal'       => $detail['subtotal'],
                                    ]);
                                    $submittedIds[] = $detailId;
                                } else {
                                    // Create new
                                    $record->orderDetails()->create([
                                        'service_id'     => $detail['service_id'],
                                        'qty'            => $detail['qty'],
                                        'harga_saat_ini' => $detail['harga_saat_ini'],
                                        'subtotal'       => $detail['subtotal'],
                                    ]);
                                }
                            }

                            // Delete removed items
                            $idsToDelete = array_diff($existingIds, $submittedIds);
                            if (!empty($idsToDelete)) {
                                $record->orderDetails()->whereIn('id_order_details', $idsToDelete)->delete();
                            }

                            // Handle Tracking Status Update
                            $newStatus = $data['tracking_status'] ?? null;
                            $currentStatus = $record->latestStatus?->status;
                            if ($newStatus && $newStatus !== $currentStatus) {
                                $record->trackings()->create(['status' => $newStatus]);
                            }
                        });

                        Notification::make()
                            ->title('Order berhasil diupdate')
                            ->body('Update berhasil disimpan.')
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

                            // Handle Initial Tracking Status
                            if (!empty($data['tracking_status'])) {
                                $order->trackings()->create(['status' => $data['tracking_status']]);
                            }

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

                ExportAction::make()
                    ->label('Export')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->exports([
                        ExcelExport::make('Data Order Laundry')
                            ->withFilename(fn () => 'Sibersih-Laporan-Laundry-' . date('Y-m-d'))
                            ->withColumns([
                                Column::make('id_orders')->heading('ID Order'),
                                
                                // Relasi Customer
                                Column::make('customer.nama_lengkap')->heading('Nama Pelanggan'),
                                Column::make('customer.no_wa')->heading('No WhatsApp'),
                                
                                // Format Tanggal
                                Column::make('tgl_masuk')
                                    ->heading('Tanggal Masuk')
                                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y')),
                                
                                Column::make('tgl_selesai_estimasi')
                                    ->heading('Estimasi Selesai')
                                    ->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('d/m/Y') : '-'),

                                // Hitung Jumlah Item
                                Column::make('orderDetails_count')
                                    ->heading('Jml Item')
                                    ->getStateUsing(fn ($record) => $record->orderDetails()->count())
                                    ,

                                // Ambil Status Terakhir (Relasi)
                                Column::make('latestStatus.status')
                                    ->heading('Status Laundry')
                                    ->formatStateUsing(fn ($state) => $state ?? 'Baru Masuk'),

                                // Custom Boolean (Pickup)
                                Column::make('is_pickup')
                                    ->heading('Layanan Jemput')
                                    ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),

                                // Status Pembayaran
                                Column::make('status_pembayaran')
                                    ->heading('Status Bayar'),

                                // Uang (Format Numerik untuk Excel agar bisa disum)
                                Column::make('total_harga')
                                    ->heading('Total Tagihan')
                                    ->formatStateUsing(fn ($state) => (float) $state), // Pastikan jadi angka murni di excel

                            ])
                        ]),

                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        DB::transaction(function () use ($records) {
                            foreach ($records as $record) {
                                $record->orderDetails()->delete();
                                $record->delete();
                            }
                        });

                        Notification::make()
                            ->title('Order berhasil dihapus')
                            ->danger()
                            ->send();
                })
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

                        Select::make('tracking_status')
                            ->label('Status Laundry')
                            ->options([
                                'Dicuci' => 'Dicuci',
                                'Dijemur' => 'Dijemur',
                                'Disetrika' => 'Disetrika',
                                'Siap' => 'Siap',
                            ])
                            ->required()
                            ->default('Dicuci'),

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

                        TextInput::make('customer.no_wa')
                            ->label('No WA')
                            ->disabled()
                            ->readOnly()
                            ->copyable('1'),

                        TextInput::make('customer.email')
                            ->label('Email')
                            ->disabled()
                            ->readOnly()
                            ->copyable('1'),

                        Textarea::make('customer.alamat')
                            ->label('Alamat')
                            ->rows(10)
                            ->disabled()
                            ->readonly()
                            ->columns(150),
                    ])->columns(3),

                Section::make('Detail Laundry')
                    ->schema([
                        Repeater::make('orderDetails')
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            })
                            ->schema([
                                Hidden::make('id_order_details'),
                                Select::make('service_id')
                                    ->label('Layanan')
                                    ->options(Service::where('is_active', true)->pluck('nama_paket', 'id_services'))
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
