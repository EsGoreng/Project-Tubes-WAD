<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Customer;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Concerns\InteractsWithForms;

use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;

use Illuminate\Database\Eloquent\Builder;

class CustomerTable extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithForms;

    // Properties untuk API Hari Libur Nasional
    public $holidays = [];
    public $upcomingHoliday = null;

    public function mount()
    {
        // Load data hari libur saat komponen dimuat
        $this->fetchHolidays();
    }

    /**
     * API HARI LIBUR NASIONAL
     * Sesuai dokumen: Untuk analisis pola kunjungan pelanggan
     */
    public function fetchHolidays()
    {
        try {
            $year = now()->year;
            
            $response = Http::timeout(5)->get("https://api-harilibur.vercel.app/api", [
                'year' => $year
            ]);

            if ($response->successful()) {
                $allHolidays = collect($response->json());
                
                // Ambil hari libur yang akan datang
                $this->upcomingHoliday = $allHolidays
                    ->filter(function($holiday) {
                        return strtotime($holiday['holiday_date']) >= strtotime('today');
                    })
                    ->first();

                // Simpan semua hari libur untuk analisis
                $this->holidays = $allHolidays->toArray();
            }
        } catch (\Exception $e) {
            $this->holidays = [];
            $this->upcomingHoliday = null;
        }
    }

    /**
     * Generate ID Customer otomatis (Simple: 1, 2, 3, ...)
     * Robust untuk VARCHAR atau INT
     */
    private function generateCustomerId()
    {
        $lastCustomer = Customer::orderBy('id_customer', 'desc')->first();

        if ($lastCustomer && is_numeric($lastCustomer->id_customer)) {
            return (int) $lastCustomer->id_customer + 1;
        }

        // Jika database kosong atau id bukan angka, mulai dari 1
        return 1;
    }

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->heading('CRUD Pelanggan (CRM)')
            ->description('Tabel ini berfungsi untuk mengelola data pelanggan dan menganalisis riwayat transaksi')
            ->query(Customer::query()
                ->select('id_customer', 'nama_lengkap', 'no_wa', 'email', 'alamat', 'description')
                ->withCount('orders'))
            ->columns([
                TextColumn::make('id_customer')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->alignCenter()
                    ->weight('bold'),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('no_wa')
                    ->label('No. WhatsApp')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->copyMessage('Nomor WhatsApp berhasil disalin!')
                    ->description(fn(Customer $record) => $record->email ?? '-'),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(30)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('orders_count')
                    ->label('Jlh Tr')
                    ->alignCenter()
                    ->badge()
                    ->color(fn($state) => $state > 5 ? 'success' : ($state > 0 ? 'warning' : 'gray'))
                    ->sortable(),

                TextColumn::make('customer_status')
                    ->label('Sts CS')
                    ->badge()
                    ->getStateUsing(function (Customer $record) {
                        $count = $record->orders_count;
                        if ($count == 0) return 'Baru';
                        if ($count >= 1 && $count <= 3) return 'Aktif';
                        if ($count > 3) return 'Setia';
                        return 'Aktif';
                    })
                    ->color(fn($state) => match($state) {
                        'Setia' => 'success',
                        'Aktif' => 'warning',
                        'Baru' => 'gray',
                        default => 'gray'
                    })
                    ->alignCenter(),
            ])
            ->filters([
                Filter::make('pelanggan_aktif')
                    ->label('Pelanggan Aktif')
                    ->query(fn (Builder $query): Builder => $query->has('orders'))
                    ->toggle(),

                Filter::make('pelanggan_loyal')
                    ->label('Pelanggan Loyal (>5 transaksi)')
                    ->query(fn (Builder $query): Builder => $query->withCount('orders')->having('orders_count', '>', 5))
                    ->toggle(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Riwayat')
                    ->color('info')
                    ->icon('heroicon-o-clock')
                    ->record(fn(Customer $record) => $record)
                    ->modalHeading(fn(Customer $record) => 'Riwayat Transaksi - ' . $record->nama_lengkap)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('7xl')
                    ->schema(fn(Schema $schema) => $this->getCustomerDetailForm($schema))
                    ->mountUsing(function (Schema $schema, Customer $record) {
                        $record->load(['orders.orderDetails.service', 'orders.latestStatus']);
                        $data = $record->toArray();
                        $data['total_transaksi'] = $record->orders->count();
                        $data['total_belanja'] = $record->orders->sum('total_harga');
                        $data['orders'] = $record->orders->map(function($order) {
                            return [
                                'id_orders' => $order->id_orders,
                                'tgl_masuk' => Carbon::parse($order->tgl_masuk)->format('d M Y'),
                                'tgl_selesai_estimasi' => $order->tgl_selesai_estimasi ? Carbon::parse($order->tgl_selesai_estimasi)->format('d M Y') : '-',
                                'status' => $order->latestStatus?->status ?? 'Baru Masuk',
                                'status_pembayaran' => $order->status_pembayaran,
                                'total_harga' => number_format($order->total_harga, 0, ',', '.'),
                                'order_details' => $order->orderDetails->map(function($detail) {
                                    return [
                                        'layanan' => $detail->service->nama_paket ?? '-',
                                        'qty' => $detail->qty,
                                        'harga_unit' => number_format($detail->harga_saat_ini, 0, ',', '.'),
                                        'subtotal' => number_format($detail->subtotal, 0, ',', '.'),
                                    ];
                                })->toArray()
                            ];
                        })->toArray();
                        $schema->fill($data);
                    })
                    ->disabledSchema(),

                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->record(fn(Customer $record) => $record)
                    ->mountUsing(function (Schema $schema, Customer $record) {
                        $schema->fill($record->toArray());
                    })
                    ->schema(fn(Schema $schema) => $this->getCustomerForm($schema))
                    ->action(function (array $data, Customer $record) {
                        $record->update([
                            'nama_lengkap' => $data['nama_lengkap'],
                            'no_wa'        => $data['no_wa'],
                            'alamat'       => $data['alamat'],
                            'email'        => $data['email'],
                            'description'  => $data['description'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Data pelanggan berhasil diperbarui')
                            ->body('Data kontak pelanggan telah diupdate.')
                            ->success()
                            ->send();
                    }),

                Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(fn(Customer $record) => $record->orders()->count() > 0 
                        ? '⚠️ PERINGATAN: Pelanggan Memiliki Riwayat Transaksi' 
                        : 'Hapus Data Pelanggan')
                    ->modalDescription(fn(Customer $record) => $record->orders()->count() > 0
                        ? 'Pelanggan ini memiliki ' . $record->orders()->count() . ' transaksi. Menghapus pelanggan akan tetap mempertahankan data transaksi, tetapi data pelanggan tidak dapat dikembalikan. Apakah Anda yakin ingin melanjutkan?'
                        : 'Apakah Anda yakin ingin menghapus data pelanggan ini?')
                    ->modalSubmitActionLabel(fn(Customer $record) => $record->orders()->count() > 0 
                        ? 'Ya, Hapus Tetap' 
                        : 'Hapus')
                    ->record(fn(Customer $record) => $record)
                    ->action(function (Customer $record) {
                        $orderCount = $record->orders()->count();
                        
                        // Hapus tetap, tidak peduli ada transaksi atau tidak
                        $record->delete();

                        if ($orderCount > 0) {
                            Notification::make()
                                ->title('Pelanggan berhasil dihapus')
                                ->body("Pelanggan dengan {$orderCount} transaksi berhasil dihapus. Data transaksi tetap tersimpan.")
                                ->warning()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Pelanggan berhasil dihapus')
                                ->body('Data pelanggan berhasil dihapus dari sistem.')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->headerActions([
                Action::make('add')
                    ->label('Tambah Pelanggan')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->schema(fn(Schema $schema) => $this->getCustomerForm($schema))
                    ->action(function (array $data) {
                        // Auto-generate ID Customer
                        $customerId = $this->generateCustomerId();

                        Customer::create([
                            'id_customer' => $customerId,
                            'nama_lengkap' => $data['nama_lengkap'],
                            'no_wa'        => $data['no_wa'],
                            'alamat'       => $data['alamat'],
                            'email'        => $data['email'],
                            'description'  => $data['description'] ?? null,
                            'password'     => bcrypt($data['password'] ?? 'password123'),
                        ]);

                        Notification::make()
                            ->title('Pelanggan berhasil ditambahkan')
                            ->body("ID Pelanggan: {$customerId}")
                            ->success()
                            ->send();
                    }),

                ExportAction::make()
                    ->label('Export')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->exports([
                        ExcelExport::make('Data Pelanggan Laundry')
                            ->withFilename(fn () => 'Sibersih-Data-Pelanggan-' . date('Y-m-d'))
                            ->withColumns([
                                Column::make('id_customer')->heading('ID'),
                                Column::make('nama_lengkap')->heading('Nama Lengkap'),
                                Column::make('no_wa')->heading('No WhatsApp'),
                                Column::make('email')->heading('Email'),
                                Column::make('alamat')->heading('Alamat'),
                                
                                Column::make('orders_count')
                                    ->heading('Total Transaksi')
                                    ->getStateUsing(fn ($record) => $record->orders()->count()),

                                Column::make('created_at')
                                    ->heading('Terdaftar Sejak')
                                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('d/m/Y')),
                            ])
                    ]),

                BulkAction::make('delete')
                    ->label('Hapus Terpilih')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $deleted = 0;
                        $failed = 0;

                        foreach ($records as $record) {
                            if ($record->orders()->count() > 0) {
                                $failed++;
                            } else {
                                $record->delete();
                                $deleted++;
                            }
                        }

                        if ($deleted > 0) {
                            Notification::make()
                                ->title("$deleted pelanggan berhasil dihapus")
                                ->success()
                                ->send();
                        }

                        if ($failed > 0) {
                            Notification::make()
                                ->title("$failed pelanggan tidak dapat dihapus")
                                ->body('Pelanggan memiliki riwayat transaksi.')
                                ->warning()
                                ->send();
                        }
                    }),
            ]);
    }

    /**
     * Form Schema untuk Create & Edit Pelanggan
     * Sesuai mockup: Nama, WhatsApp, Email, Alamat
     */
    public function getCustomerForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Data Pelanggan')
                    ->description('Masukkan data pelanggan. Nomor WhatsApp akan digunakan untuk komunikasi layanan.')
                    ->schema([
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required()
                            ->minLength(3)
                            ->maxLength(100)
                            ->placeholder('Contoh: Budi Santoso'),

                        TextInput::make('no_wa')
                            ->label('Nomor WhatsApp')
                            ->required()
                            ->tel()
                            ->prefix('+62')
                            ->placeholder('812XXXXXXXX')
                            ->minLength(10)
                            ->maxLength(15)
                            ->unique(ignoreRecord: true)
                            ->rules(['regex:/^[0-9]+$/']),

                        TextInput::make('email')
                            ->label('Email (Opsional)')
                            ->email()
                            ->maxLength(100)
                            ->placeholder('email@example.com'),

                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(4)
                            ->minLength(10)
                            ->maxLength(255)
                            ->placeholder('Jl. Contoh No.123, Kota, Provinsi')
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Catatan Tambahan (Opsional)')
                            ->rows(3)
                            ->maxLength(255)
                            ->placeholder('Contoh: Mahasiswa Telkom, Langganan rutin setiap minggu')
                            ->helperText('Anda dapat menambahkan catatan khusus tentang pelanggan ini')
                            ->columnSpanFull(),

                        TextInput::make('password')
                            ->label('Password (Hanya untuk pelanggan baru)')
                            ->password()
                            ->minLength(6)
                            ->default('password123')
                            ->helperText('Default: password123')
                            ->dehydrated(fn ($state) => filled($state))
                            ->visible(fn ($livewire) => $livewire instanceof \Filament\Actions\CreateAction),
                    ])->columns(2)
            ]);
    }

    /**
     * Form Schema untuk View Detail & Riwayat Transaksi
     * Sesuai mockup hal. 31: Lihat riwayat order pelanggan dengan detail laundry
     */
    public function getCustomerDetailForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pelanggan')
                    ->schema([
                        TextInput::make('id_customer')
                            ->label('ID Pelanggan')
                            ->disabled(),

                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->disabled(),

                        TextInput::make('no_wa')
                            ->label('No. WhatsApp')
                            ->disabled()
                            ->copyable(),

                        TextInput::make('email')
                            ->label('Email')
                            ->disabled(),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->disabled()
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(4),

                Section::make('Statistik Transaksi')
                    ->schema([
                        TextInput::make('total_transaksi')
                            ->label('Total Transaksi')
                            ->disabled()
                            ->suffix('transaksi'),

                        TextInput::make('total_belanja')
                            ->label('Total Belanja')
                            ->disabled()
                            ->prefix('Rp')
                            ->numeric(),
                    ])->columns(2),

                Section::make('Riwayat Transaksi')
                    ->description('Detail lengkap setiap transaksi pelanggan')
                    ->schema([
                        Repeater::make('orders')
                            ->label('')
                            ->schema([
                                TextInput::make('id_orders')
                                    ->label('ID Order')
                                    ->disabled(),

                                TextInput::make('tgl_masuk')
                                    ->label('Tanggal Masuk')
                                    ->disabled(),

                                TextInput::make('tgl_selesai_estimasi')
                                    ->label('Estimasi Selesai')
                                    ->disabled(),

                                TextInput::make('status')
                                    ->label('Status Laundry')
                                    ->disabled(),

                                TextInput::make('status_pembayaran')
                                    ->label('Status Bayar')
                                    ->disabled(),

                                TextInput::make('total_harga')
                                    ->label('Total Tagihan')
                                    ->prefix('Rp')
                                    ->disabled(),

                                Section::make('Detail Laundry')
                                    ->description('Rincian paket layanan yang dipesan')
                                    ->schema([
                                        Repeater::make('order_details')
                                            ->label('')
                                            ->schema([
                                                TextInput::make('layanan')
                                                    ->label('Layanan')
                                                    ->disabled(),

                                                TextInput::make('qty')
                                                    ->label('Qty (Kg/Pcs)')
                                                    ->disabled(),

                                                TextInput::make('harga_unit')
                                                    ->label('Harga/Unit')
                                                    ->prefix('Rp')
                                                    ->disabled(),

                                                TextInput::make('subtotal')
                                                    ->label('Subtotal')
                                                    ->prefix('Rp')
                                                    ->disabled(),
                                            ])
                                            ->columns(4)
                                            ->addable(false)
                                            ->deletable(false)
                                            ->reorderable(false)
                                            ->defaultItems(0)
                                    ])->columnSpanFull()
                            ])
                            ->columns(3)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->defaultItems(0)
                            ->columnSpanFull()
                    ])
            ]);
    }

    public function render()
    {
        return view('livewire.customer-table');
    }
}