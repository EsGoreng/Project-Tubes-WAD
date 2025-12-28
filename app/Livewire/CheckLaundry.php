<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Filament\Tables\Table;
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
                TextColumn::make('tgl_masuk')
                    ->label('Tanggal Masuk')
                    ->dateTime('d F Y H:i')
                    ->sortable(),
                TextColumn::make('latestStatus.status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('orderDetails.service.nama_layanan')
                    ->label('Layanan')
                    ->listWithLineBreaks(),
            ])
            ->defaultSort('tgl_masuk', 'desc');
    }

    public function render(): View
    {
        return view('livewire.check-laundry');
    }
}
