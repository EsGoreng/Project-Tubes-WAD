<div class="space-y-6">
    {{-- Header dengan Statistik --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-orange-500 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-white">Kalender Hari Libur</h2>
                        <p class="text-sm text-white/80">Hari Libur Nasional Indonesia</p>
                    </div>
                </div>
                <button wire:click="refresh" 
                        class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition-colors text-sm font-medium flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Refresh</span>
                </button>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-3 gap-3 mt-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
                    <div class="text-xs text-white/80">Total Hari Libur</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-white">{{ $stats['libur'] }}</div>
                    <div class="text-xs text-white/80">Libur Nasional</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-white">{{ $stats['cuti'] }}</div>
                    <div class="text-xs text-white/80">Cuti Bersama</div>
                </div>
            </div>
        </div>

        @if($error)
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 m-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm text-red-700 dark:text-red-200 font-medium">Terjadi Kesalahan</p>
                        <p class="text-xs text-red-600 dark:text-red-300 mt-1">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($isLoading)
            <div class="p-8 text-center">
                <svg class="animate-spin h-8 w-8 text-red-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-gray-600 dark:text-gray-400">Memuat data hari libur...</p>
            </div>
        @else
            {{-- Navigasi Bulan --}}
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <button wire:click="changeMonth('prev')" 
                            class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <div class="flex items-center space-x-3">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ $monthName }}
                        </h3>
                        <button wire:click="goToToday" 
                                class="text-sm bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 px-3 py-1 rounded-md hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                            Hari Ini
                        </button>
                    </div>

                    <button wire:click="changeMonth('next')" 
                            class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Grid Kalender --}}
            <div class="p-4">
                {{-- Header Hari --}}
                <div class="grid grid-cols-7 gap-2 mb-2">
                    @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                        <div class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 py-2">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                {{-- Grid Tanggal --}}
                <div class="grid grid-cols-7 gap-2">
                    @foreach($calendarDays as $day)
                        <div class="relative group">
                            <div class="aspect-square rounded-lg transition-all flex items-center justify-center
                                {{ $day['is_today'] && !$day['is_holiday'] ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30' : '' }}
                                {{ $day['is_today'] && $day['is_holiday'] ? 'ring-2 ring-blue-500 bg-red-100 dark:bg-red-900/50' : '' }}
                                {{ $day['is_holiday'] && !$day['is_today'] ? 'bg-red-100 dark:bg-red-900/50' : '' }}
                                {{ $day['is_weekend'] && !$day['is_holiday'] && !$day['is_today'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}
                                {{ !$day['is_current_month'] ? 'opacity-40' : '' }}
                                {{ !$day['is_today'] && !$day['is_holiday'] && !$day['is_weekend'] ? 'hover:bg-gray-50 dark:hover:bg-gray-700' : '' }}">
                                
                                <div class="text-center">
                                    <span class="text-sm font-medium
                                        {{ $day['is_today'] && !$day['is_holiday'] ? 'text-blue-700 dark:text-blue-300 font-bold' : '' }}
                                        {{ $day['is_today'] && $day['is_holiday'] ? 'text-red-700 dark:text-red-300 font-bold' : '' }}
                                        {{ $day['is_holiday'] && !$day['is_today'] ? 'text-red-700 dark:text-red-300 font-bold' : 'text-gray-700 dark:text-gray-300' }}">
                                        {{ $day['day'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Tooltip untuk Hari Libur --}}
                            @if($day['is_holiday'] && $day['holiday_info'])
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block z-10 pointer-events-none">
                                    <div class="bg-gray-900 text-white text-xs rounded-lg py-2 px-3 whitespace-nowrap shadow-lg">
                                        <div class="font-semibold">{{ $day['holiday_info']['keterangan'] }}</div>
                                        <div class="text-gray-300 mt-1">{{ $day['holiday_info']['tanggal_display'] }}</div>
                                        @if(isset($day['holiday_info']['is_cuti']) && $day['holiday_info']['is_cuti'])
                                            <div class="text-yellow-300 text-xs mt-1">🟡 Cuti Bersama</div>
                                        @endif
                                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-px">
                                            <div class="border-4 border-transparent border-t-gray-900"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Keterangan --}}
                <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-gray-600 dark:text-gray-400 border-t border-gray-200 dark:border-gray-600 pt-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-100 dark:bg-red-900/50 border border-red-300 dark:border-red-700 rounded mr-2"></div>
                        <span>Hari Libur</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-50 dark:bg-blue-900/30 border-2 border-blue-500 rounded mr-2"></div>
                        <span>Hari Ini</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded mr-2"></div>
                        <span>Weekend</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Hari Libur Mendatang --}}
    @if(!$isLoading && !empty($upcomingHolidays))
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 p-4">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Hari Libur Mendatang
                </h3>
            </div>

            <div class="p-4 space-y-3">
                @foreach($upcomingHolidays as $holiday)
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-lg p-3 border-l-4 
                        {{ isset($holiday['is_cuti']) && $holiday['is_cuti'] ? 'border-yellow-500' : 'border-red-500' }}">
                        
                        <div class="flex items-start justify-between mb-2">
                            <span class="inline-block bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-200 text-xs font-semibold px-2 py-1 rounded-full">
                                @if($holiday['days_until'] == 0)
                                    Hari Ini
                                @elseif($holiday['days_until'] == 1)
                                    Besok
                                @else
                                    {{ $holiday['days_until'] }} hari lagi
                                @endif
                            </span>
                            
                            @if(isset($holiday['is_cuti']) && $holiday['is_cuti'])
                                <span class="inline-block bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 text-xs px-2 py-1 rounded-full">
                                    Cuti Bersama
                                </span>
                            @endif
                        </div>

                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1 text-sm">
                            {{ $holiday['keterangan'] }}
                        </h4>

                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            {{ $holiday['tanggal_display'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 p-3 text-center border-t border-gray-200 dark:border-gray-600">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Sumber data: <a href="https://dayoffapi.vercel.app" target="_blank" class="text-red-600 dark:text-red-400 hover:underline">Dayoff API</a>
                </p>
            </div>
        </div>
    @endif

    {{-- Quick Year Navigation --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-4">
            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3 text-sm flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Pilih Tahun
            </h4>
            <div class="grid grid-cols-4 gap-2">
                @php
                    $years = range(now()->year - 1, now()->year + 2);
                @endphp
                @foreach($years as $year)
                    <button wire:click="changeYear({{ $year }})" 
                            class="px-3 py-2 rounded-lg text-sm transition-colors font-medium
                            {{ $selectedYear == $year 
                                ? 'bg-red-500 text-white' 
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        {{ $year }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>