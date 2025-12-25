<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Service;
use Livewire\Component;

use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use Filament\Notifications\Notification;

use Illuminate\Database\Eloquent\Builder;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;

class ServiceTable extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->heading('CRUD Paket Layanan')
            ->description('Tabel ini berfungsi untuk mengelola paket layanan laundry (harga, estimasi, dan deskripsi)')
            ->query(Service::query())
            ->columns([
                TextColumn::make('id_services')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_paket')
                    ->label('Service Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(Service $record) => $record->deskripsi ? substr($record->deskripsi, 0, 50) . '...' : '-'),

                TextColumn::make('harga')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('satuan')
                    ->label('Unit')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Kg' => 'info',
                        'Pcs' => 'warning',
                        default => 'gray',
                    })
                    ->alignCenter(),

                TextColumn::make('estimasi_durasi')
                    ->label('Estimation (Hours)')
                    ->suffix(' jam')
                    ->alignCenter()
                    ->color('primary'),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->onColor('success')
                    ->offColor('danger')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua Paket')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),

                Filter::make('harga')
                    ->form([
                        TextInput::make('harga_min')
                            ->label('Harga Minimum')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('harga_max')
                            ->label('Harga Maximum')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['harga_min'],
                                fn(Builder $query, $harga) => $query->where('harga', '>=', $harga),
                            )
                            ->when(
                                $data['harga_max'],
                                fn(Builder $query, $harga) => $query->where('harga', '<=', $harga),
                            );
                    }),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->color('success')
                    ->icon('heroicon-o-eye')
                    ->record(fn(Service $record) => $record)
                    ->modalHeading('Detail Paket Layanan')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->schema(fn(Schema $schema) => $this->getServiceForm($schema))
                    ->mountUsing(function (Schema $schema, Service $record) {
                        $schema->fill($record->toArray());
                    })
                    ->disabledSchema(),

                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->record(fn(Service $record) => $record)
                    ->mountUsing(function (Schema $schema, Service $record) {
                        $schema->fill($record->toArray());
                    })
                    ->schema(fn(Schema $schema) => $this->getServiceForm($schema))
                    ->action(function (array $data, Service $record) {
                        $record->update([
                            'nama_paket'       => $data['nama_paket'],
                            'harga'            => $data['harga'],
                            'satuan'           => $data['satuan'],
                            'estimasi_durasi'  => $data['estimasi_durasi'],
                            'deskripsi'        => $data['deskripsi'],
                            'is_active'        => $data['is_active'],
                        ]);

                        Notification::make()
                            ->title('Paket layanan berhasil diupdate')
                            ->body("Paket '{$record->nama_paket}' berhasil diperbarui.")
                            ->success()
                            ->send();
                    }),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Paket Layanan')
                    ->modalDescription('Apakah Anda yakin ingin menghapus paket ini? Data yang sudah dihapus tidak dapat dikembalikan.')
                    ->record(fn(Service $record) => $record)
                    ->action(function (Service $record) {
                        $namaParket = $record->nama_paket;
                        $record->delete();

                        Notification::make()
                            ->title('Paket layanan berhasil dihapus')
                            ->body("Paket '{$namaParket}' telah dihapus dari sistem.")
                            ->danger()
                            ->send();
                    }),
            ])
            ->headerActions([
                Action::make('add')
                    ->label('Tambah Paket')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->schema(fn(Schema $schema) => $this->getServiceForm($schema))
                    ->action(function (array $data) {
                        Service::create([
                            'nama_paket'       => $data['nama_paket'],
                            'harga'            => $data['harga'],
                            'satuan'           => $data['satuan'],
                            'estimasi_durasi'  => $data['estimasi_durasi'],
                            'deskripsi'        => $data['deskripsi'],
                            'is_active'        => $data['is_active'] ?? true,
                        ]);

                        Notification::make()
                            ->title('Paket layanan berhasil ditambahkan')
                            ->body("Paket '{$data['nama_paket']}' berhasil disimpan.")
                            ->success()
                            ->send();
                    }),

                ExportAction::make()
                    ->label('Export Daftar Harga')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('warning')
                    ->exports([
                        ExcelExport::make('Daftar Harga Paket Layanan')
                            ->withFilename(fn() => 'SiBersih-Daftar-Harga-' . date('Y-m-d'))
                            ->withColumns([
                                Column::make('id_services')->heading('ID'),
                                Column::make('nama_paket')->heading('Nama Paket'),
                                
                                Column::make('harga')
                                    ->heading('Harga (Rp)')
                                    ->formatStateUsing(fn($state) => (float) $state),
                                
                                Column::make('satuan')->heading('Satuan'),
                                
                                Column::make('estimasi_durasi')
                                    ->heading('Estimasi (Jam)')
                                    ->formatStateUsing(fn($state) => $state . ' jam'),
                                
                                Column::make('deskripsi')->heading('Deskripsi'),
                                
                                Column::make('is_active')
                                    ->heading('Status')
                                    ->formatStateUsing(fn($state) => $state ? 'Aktif' : 'Nonaktif'),
                                
                                Column::make('created_at')
                                    ->heading('Dibuat Pada')
                                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('d/m/Y H:i')),
                            ])
                    ]),

                Action::make('bulk_activate')
                    ->label('Aktifkan Semua')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function () {
                        $count = Service::where('is_active', false)->update(['is_active' => true]);

                        Notification::make()
                            ->title('Paket layanan diaktifkan')
                            ->body("{$count} paket berhasil diaktifkan.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkAction::make('activate')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $records->each->update(['is_active' => true]);

                        Notification::make()
                            ->title('Paket diaktifkan')
                            ->body("{$records->count()} paket berhasil diaktifkan.")
                            ->success()
                            ->send();
                    }),

                BulkAction::make('deactivate')
                    ->label('Nonaktifkan')
                    ->icon('heroicon-o-x-mark')
                    ->color('warning')
                    ->action(function (Collection $records) {
                        $records->each->update(['is_active' => false]);

                        Notification::make()
                            ->title('Paket dinonaktifkan')
                            ->body("{$records->count()} paket berhasil dinonaktifkan.")
                            ->warning()
                            ->send();
                    }),

                BulkAction::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Paket Terpilih')
                    ->modalDescription('Apakah Anda yakin ingin menghapus paket-paket yang dipilih?')
                    ->action(function (Collection $records) {
                        DB::transaction(function () use ($records) {
                            $records->each->delete();
                        });

                        Notification::make()
                            ->title('Paket berhasil dihapus')
                            ->body("{$records->count()} paket telah dihapus dari sistem.")
                            ->danger()
                            ->send();
                    }),
            ]);
    }

    public function getServiceForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Paket Layanan')
                    ->description('Masukkan detail paket layanan laundry yang akan ditawarkan')
                    ->schema([
                        TextInput::make('nama_paket')
                            ->label('Service Name')
                            ->placeholder('Contoh: Cuci Komplit, Cuci Setrika, Cuci Sepatu')
                            ->required()
                            ->maxLength(100)
                            ->columnSpan(2),

                        TextInput::make('harga')
                            ->label('Price')
                            ->placeholder('0')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->maxValue(9999999)
                            ->helperText('Harga per satuan (Kg atau Pcs)'),

                        Select::make('satuan')
                            ->label('Unit')
                            ->options([
                                'Kg' => 'Kilogram (Kg)',
                                'Pcs' => 'Pieces (Pcs)',
                            ])
                            ->required()
                            ->default('Kg')
                            ->helperText('Satuan perhitungan harga'),

                        TextInput::make('estimasi_durasi')
                            ->label('Estimation (Hours)')
                            ->placeholder('0')
                            ->required()
                            ->numeric()
                            ->suffix('jam')
                            ->minValue(1)
                            ->maxValue(240)
                            ->default(24)
                            ->helperText('Estimasi waktu pengerjaan dalam jam'),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Paket aktif akan muncul di menu kasir dan portal pelanggan')
                            ->default(true)
                            ->inline(false),

                        Textarea::make('deskripsi')
                            ->label('Description')
                            ->placeholder('Contoh: Layanan cuci + setrika + lipat rapi. Cocok untuk pakaian harian.')
                            ->rows(4)
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->helperText('Deskripsi detail layanan (opsional, maks 500 karakter)')
                    ])
                    ->columns(2),

                Section::make('Informasi Tambahan')
                    ->description('Informasi metadata (otomatis terisi)')
                    ->schema([
                        TextInput::make('created_at')
                            ->label('Dibuat Pada')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(now()->format('d M Y H:i')),

                        TextInput::make('updated_at')
                            ->label('Diperbarui Pada')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(now()->format('d M Y H:i')),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->visibleOn(['view', 'edit']),
            ]);
    }

    public function render()
    {
        return view('livewire.service-table');
    }
}