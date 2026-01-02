<div>
    <div class="space-y-6 mt-6">
        <!-- Header -->
        <div class="bg-gray-800 dark:bg-gray-800/50 rounded-lg shadow-sm p-6 ">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">Pilih Layanan Laundry</h2>
                    <p class="text-gray-400 mt-1">Pilih paket layanan yang Anda butuhkan</p>
                </div>

                @if (count($selectedServices) > 0)
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Total Item</p>
                            <p class="text-2xl font-bold text-primary-600">{{ count($selectedServices) }}</p>
                        </div>
                        <x-filament::button wire:click="createOrder" icon="heroicon-o-shopping-cart" size="lg">
                            Checkout
                        </x-filament::button>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Service Table -->
            <div class="lg:col-span-2">
                {{ $this->table }}
            </div>

            <!-- Cart Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-gray-800 dark:bg-gray-800/50 rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Keranjang Belanja
                    </h3>

                    @if (count($selectedServices) > 0)
                        <div class="space-y-3 mb-4 max-h-96 overflow-y-auto mt-4">
                            @foreach ($selectedServices as $serviceId => $service)
                                <div class="border border-gray-800 rounded-lg p-3">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-sm text-gray-200">{{ $service->nama_paket }}
                                            </h4>
                                            <p class="text-xs text-gray-400 mb-1">Rp
                                                {{ number_format($service->harga, 0, ',', '.') }}/{{ $service->satuan }}
                                            </p>
                                        </div>
                                        <button wire:click="removeFromCart({{ $serviceId }})"
                                            class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <button
                                                wire:click="updateQuantity({{ $serviceId }}, {{ $quantities[$serviceId] - 1 }})"
                                                class="w-7 h-7 rounded-full bg-gray-800 hover:bg-gray-400 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>

                                            <input type="number" wire:model.blur="quantities.{{ $serviceId }}"
                                                class="w-16 text-center border border-gray-600 rounded py-1 text-sm bg-gray-800 text-white"
                                                min="1">

                                            <button
                                                wire:click="updateQuantity({{ $serviceId }}, {{ $quantities[$serviceId] + 1 }})"
                                                class="w-7 h-7 rounded-full bg-gray-800 hover:bg-gray-400 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm font-bold text-gray-400">
                                                Rp
                                                {{ number_format($service->harga * $quantities[$serviceId], 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-lg font-semibold text-white    ">Total</span>
                                <span class="text-2xl font-bold text-primary-600">
                                    Rp {{ number_format($this->getTotal(), 0, ',', '.') }}
                                </span>
                            </div>

                            <x-filament::button wire:click="createOrder" color="primary" icon="heroicon-o-shopping-cart"
                                class="w-full" size="lg">
                                Lanjut ke Checkout
                            </x-filament::button>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <p class="text-gray-500 text-sm">Keranjang masih kosong</p>
                            <p class="text-gray-400 text-xs mt-1">Pilih layanan dari tabel</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <x-filament::modal id="checkout-modal" width="2xl" :close-by-clicking-away="false">
        <x-slot name="heading">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Konfirmasi Pesanan
            </div>
        </x-slot>

        <div class="space-y-6">
            <!-- Ringkasan Pesanan -->
            <div class="bg-gray-800 rounded-lg p-4">
                <h4 class="font-semibold text-white mb-3">Ringkasan Pesanan</h4>
                <div class="space-y-2">
                    @foreach ($selectedServices as $serviceId => $service)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-200">{{ $service->nama_paket }} ({{ $quantities[$serviceId] }}
                                {{ $service->satuan }})</span>
                            <span class="font-medium">Rp
                                {{ number_format($service->harga * $quantities[$serviceId], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <div class="border-t pt-2 mt-2 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-primary-600">Rp {{ number_format($this->getTotal(), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Checkout -->
            <form wire:submit="processOrder">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            Metode Pengiriman <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label
                                class="flex items-start p-4 rounded-lg cursor-pointer bg-gray-800 hover:bg-gray-700 transition"
                                :class="$wire.checkout_is_pickup === '0' ? 'bg-gray-800' :
                                    'border-gray-200'">
                                <input type="radio" wire:model.live="checkout_is_pickup" value="0"
                                    class="mt-1">
                                <div class="ml-3">
                                    <div class="font-medium text-white">Antar Sendiri</div>
                                    <div class="text-sm text-gray-400">Anda akan mengantar cucian langsung ke laundry
                                    </div>
                                </div>
                            </label>

                            <label
                                class="flex items-start p-4 rounded-lg cursor-pointer bg-gray-800 hover:bg-gray-700 transition"
                                :class="$wire.checkout_is_pickup === '1' ? 'bg-gray-800' :
                                    'border-gray-200'">
                                <input type="radio" wire:model.live="checkout_is_pickup" value="1"
                                    class="mt-1">
                                <div class="ml-3">
                                    <div class="font-medium text-white">Dijemput</div>
                                    <div class="text-sm text-gray-400">Kami akan menjemput cucian di alamat Anda</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    @if ($checkout_is_pickup === '1')
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">
                                Alamat Penjemputan <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="checkout_alamat" rows="3"
                                class="w-full border-gray-600 rounded-lg shadow-sm focus:border-gray-800 focus:ring-gray-700 text-white bg-gray-800"
                                placeholder="Masukkan alamat lengkap untuk penjemputan..."></textarea>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">
                            Catatan Tambahan (Opsional)
                        </label>
                        <textarea wire:model="checkout_catatan" rows="3"
                            class="w-full border-gray-600 rounded-lg shadow-sm focus:border-gray-800 focus:ring-gray-700 text-white bg-gray-800"
                            placeholder="Contoh: pisahkan warna, lipat rapi, dll..."></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <x-filament::button type="button" color="gray"
                        x-on:click="$dispatch('close-modal', { id: 'checkout-modal' })" class="flex-1">
                        Batal
                    </x-filament::button>

                    <x-filament::button type="submit" color="primary" icon="heroicon-o-check-circle"
                        class="flex-1">
                        Konfirmasi Pesanan
                    </x-filament::button>
                </div>
            </form>
        </div>
    </x-filament::modal>

    @script
        <script>
            $wire.on('open-checkout-modal', () => {
                $dispatch('open-modal', {
                    id: 'checkout-modal'
                });
            });

            $wire.on('close-checkout-modal', () => {
                $dispatch('close-modal', {
                    id: 'checkout-modal'
                });
            });
        </script>
    @endscript
</div>
