@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="bg-linear-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::guard('customer')->user()->nama_lengkap }}!</h1>
            <p class="text-blue-100">Kelola pesanan laundry Anda dengan mudah</p>
        </div>

        <div x-data="{ activeTab: 'order' }">
            <x-filament::tabs>

                <x-filament::tabs.item @click="activeTab = 'order'" :active="false" alpine-active="activeTab === 'order'"
                    icon="heroicon-o-clipboard-document-list">
                    Pesanan Saya
                </x-filament::tabs.item>

                <x-filament::tabs.item @click="activeTab = 'booking'" :active="false"
                    alpine-active="activeTab === 'booking'" icon="heroicon-o-plus-circle">
                    Buat Pesanan Baru
                </x-filament::tabs.item>

            </x-filament::tabs>

            <!-- Tab Order (Cek Laundry) -->
            <div x-show="activeTab === 'order'" x-cloak x-transition>
                @livewire('check-laundry')
            </div>

            <div x-show="activeTab === 'booking'" x-cloak x-transition>
                @livewire('order-create')
            </div>
        </div>

        <!-- Weather Widget -->
        <div class="mt-8">
            @livewire('weather-widget')
        </div>
    </div>
@endsection
