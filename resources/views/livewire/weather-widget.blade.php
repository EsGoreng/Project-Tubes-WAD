<div class="space-y-6">
    {{-- Header dengan Location Picker & Refresh --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Prakiraan Cuaca BMKG
            </h2>
            @if ($location)
                <button wire:click="toggleLocationPicker"
                    class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mt-1 hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-medium">{{ $location['desa'] }}, {{ $location['kecamatan'] }}</span>
                    <span class="text-xs opacity-75">({{ $location['kotkab'] }})</span>
                    <svg class="w-4 h-4 transition-transform {{ $showLocationPicker ? 'rotate-180' : '' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            @endif
        </div>
        <button wire:click="refresh" wire:loading.attr="disabled"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50 shadow-sm hover:shadow">
            <svg wire:loading.remove wire:target="refresh" class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                </path>
            </svg>
            <svg wire:loading wire:target="refresh" class="animate-spin w-4 h-4 mr-2" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span wire:loading.remove wire:target="refresh">Refresh</span>
            <span wire:loading wire:target="refresh">Loading...</span>
        </button>
    </div>

    {{-- Location Selection Panel (Inline) --}}
    @if ($showLocationPicker)
        <div x-data="{ show: @entangle('showLocationPicker') }" x-show="show" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pilih Lokasi
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ count($filteredAreas) }} lokasi tersedia
                    </p>
                </div>
                <button wire:click="toggleLocationPicker"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            {{-- Search Box --}}
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="searchQuery"
                        class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm"
                        placeholder="Cari kota/kabupaten... (contoh: Bandung, Jakarta)">
                    @if ($searchQuery)
                        <button wire:click="$set('searchQuery', '')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
                @if ($searchQuery && empty($filteredAreas))
                    <p class="mt-2 text-sm text-amber-600 dark:text-amber-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        Lokasi tidak ditemukan. Coba kata kunci lain.
                    </p>
                @endif
            </div>

            {{-- Locations Grid --}}
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-96 overflow-y-auto custom-scrollbar pr-2">
                @forelse($filteredAreas as $code => $name)
                    <button wire:click="selectPopularArea('{{ $code }}')" wire:loading.attr="disabled"
                        class="flex items-center gap-3 px-3 py-2.5 text-left text-sm bg-gray-50 dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-all duration-200 group border border-transparent hover:border-blue-200 dark:hover:border-blue-800 {{ $areaCode === $code
                            ? 'outline-2 outline-blue-500 bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800'
                            : '' }}">
                        <div class="shrink-0">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors {{ $areaCode === $code ? 'text-blue-600' : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span
                            class="flex-1 text-gray-900 dark:text-white font-medium truncate">{{ $name }}</span>
                        @if ($areaCode === $code)
                            <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </button>
                @empty
                    <div class="col-span-full text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada lokasi yang cocok</p>
                    </div>
                @endforelse
            </div>

            {{-- Info --}}
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pilih lokasi terdekat untuk prakiraan yang lebih akurat
                </p>
            </div>
        </div>

        {{-- Custom Scrollbar Styles --}}
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #cbd5e0;
                border-radius: 3px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #4b5563;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>
    @endif

    {{-- Loading State --}}
    @if ($isLoading)
        <div class="flex items-center justify-center py-12">
            <div class="text-center">
                <svg class="animate-spin h-12 w-12 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Mengambil data cuaca...</p>
            </div>
        </div>
    @endif

    {{-- Error State --}}
    @if ($error && !$isLoading)
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Error</h3>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">{{ $error }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Current Weather --}}
    @if ($currentWeather && !$isLoading)
        <div class="bg-linear-to-br from-blue-500 to-blue-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Cuaca Saat Ini</p>
                    <h3 class="text-4xl font-bold mt-2">{{ $currentWeather['temperature'] }}°C</h3>
                    <p class="text-lg mt-2 capitalize">{{ $currentWeather['weather_desc'] }}</p>
                </div>
                @if ($currentWeather['image'])
                    <img src="{{ str_replace(' ', '%20', $currentWeather['image']) }}"
                        alt="{{ $currentWeather['weather_desc'] }}" class="w-24 h-24"
                        onerror="this.style.display='none'">
                @endif
            </div>

            <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-white/20">
                <div>
                    <p class="text-sm opacity-90">Kelembapan</p>
                    <p class="text-xl font-semibold">{{ $currentWeather['humidity'] }}%</p>
                </div>
                <div>
                    <p class="text-sm opacity-90">Kec. Angin</p>
                    <p class="text-xl font-semibold">{{ $currentWeather['wind_speed'] }} km/j</p>
                </div>
                <div>
                    <p class="text-sm opacity-90">Arah Angin</p>
                    <p class="text-xl font-semibold">{{ $currentWeather['wind_direction'] }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- 3 Day Forecast --}}
    @if (!empty($forecast) && !$isLoading)
        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Prakiraan 3 Hari</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($forecast as $day)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="text-center mb-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                @if ($day['day'] == 0)
                                    Hari Ini
                                @elseif($day['day'] == 1)
                                    Besok
                                @else
                                    {{ \Carbon\Carbon::parse($day['date'])->locale('id')->isoFormat('dddd') }}
                                @endif
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($day['date'])->locale('id')->isoFormat('D MMMM Y') }}
                            </p>
                        </div>

                        {{-- Pagi --}}
                        <div
                            class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                @if ($day['morning']['image'])
                                    <img src="{{ str_replace(' ', '%20', $day['morning']['image']) }}"
                                        alt="{{ $day['morning']['weather_desc'] }}" class="w-10 h-10"
                                        onerror="this.style.display='none'">
                                @endif
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Pagi</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $day['morning']['temperature'] }}°C</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $day['morning']['humidity'] }}%
                                </p>
                            </div>
                        </div>

                        {{-- Siang --}}
                        <div
                            class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                @if ($day['noon']['image'])
                                    <img src="{{ str_replace(' ', '%20', $day['noon']['image']) }}"
                                        alt="{{ $day['noon']['weather_desc'] }}" class="w-10 h-10"
                                        onerror="this.style.display='none'">
                                @endif
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Siang</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $day['noon']['temperature'] }}°C</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $day['noon']['humidity'] }}%
                                </p>
                            </div>
                        </div>

                        {{-- Malam --}}
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center space-x-3">
                                @if ($day['night']['image'])
                                    <img src="{{ str_replace(' ', '%20', $day['night']['image']) }}"
                                        alt="{{ $day['night']['weather_desc'] }}" class="w-10 h-10"
                                        onerror="this.style.display='none'">
                                @endif
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Malam</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $day['night']['temperature'] }}°C</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $day['night']['humidity'] }}%
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Footer Info --}}
    @if (!$isLoading && !$error)
        <div class="text-center text-sm text-gray-500 dark:text-gray-400">
            <p>Data prakiraan cuaca dari BMKG (Badan Meteorologi, Klimatologi, dan Geofisika)</p>
            <p class="mt-1">Terakhir diperbarui: {{ now()->locale('id')->isoFormat('D MMMM Y HH:mm') }} WIB</p>
        </div>
    @endif
</div>
