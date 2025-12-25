@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div x-data="{ activeTab: 'overview' }">

        <x-filament::tabs>

            <x-filament::tabs.item @click="activeTab = 'overview'" :active="false" alpine-active="activeTab === 'overview'">
                Overview
            </x-filament::tabs.item>

            <x-filament::tabs.item @click="activeTab = 'calendar'" :active="false" alpine-active="activeTab === 'calendar'">
                Calendar
            </x-filament::tabs.item>

            <x-filament::tabs.item @click="activeTab = 'weather'" :active="false" alpine-active="activeTab === 'weather'">
                Weather
            </x-filament::tabs.item>

        </x-filament::tabs>

        <div class="mt-4">
            <div x-show="activeTab === 'overview'" x-transition>
                <div class="p-4 bg-white shadow rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Overview</h2>
                    <p class="text-gray-600">Grafik</p>
                </div>
            </div>

            <div x-show="activeTab === 'calendar'" x-cloak x-transition>
                <div class="p-4 bg-white shadow rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Calendar</h2>
                    <p class="text-gray-600">Kalender Libur</p>
                </div>
            </div>

            <div x-show="activeTab === 'weather'" x-cloak x-transition>
                <div class="p-4 bg-white shadow rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Weather</h2>
                    <p class="text-gray-600">Weather</p>
                </div>
            </div>

        </div>
    </div>

    <div x-data="{ activeTab: 'order' }" class="mt-8">

        <x-filament::tabs>

            <x-filament::tabs.item @click="activeTab = 'order'" :active="false" alpine-active="activeTab === 'order'">
                Order
            </x-filament::tabs.item>

            <x-filament::tabs.item @click="activeTab = 'crm'" :active="false" alpine-active="activeTab === 'crm'">
                CRM
            </x-filament::tabs.item>

            <x-filament::tabs.item @click="activeTab = 'service'" :active="false"
                alpine-active="activeTab === 'service'">
                Services
            </x-filament::tabs.item>

        </x-filament::tabs>

        <div class="mt-4">
            <div x-show="activeTab === 'order'" x-transition>
                @livewire('order-table')
            </div>

            <div x-show="activeTab === 'crm'" x-cloak x-transition>
                <div class="p-4 bg-white shadow rounded-lg">
                    <h2 class="text-xl font-bold mb-4">CRM Customer</h2>
                    {{-- @livewire('crm-table') --}}
                    <p class="text-gray-600">Konten tabel customer akan muncul di sini.</p>
                </div>
            </div>

            <div x-show="activeTab === 'service'" x-cloak x-transition>
                    @livewire('service-table')
            </div>

        </div>
    </div>

@endsection
