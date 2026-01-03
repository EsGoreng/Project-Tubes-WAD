<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HolidayCalendar extends Component
{
    public $holidays = [];
    public $currentYear;
    public $currentMonth;
    public $selectedMonth;
    public $selectedYear;
    public $calendarDays = [];
    public $isLoading = true;
    public $error = null;
    public $upcomingHolidays = [];

    public function mount()
    {
        $this->currentYear = now()->year;
        $this->currentMonth = now()->month;
        $this->selectedYear = $this->currentYear;
        $this->selectedMonth = $this->currentMonth;
        
        $this->fetchHolidays();
    }

    public function fetchHolidays()
    {
        $this->isLoading = true;
        $this->error = null;

        try {
            // Cache data selama 30 hari
            $cacheKey = 'holidays_' . $this->selectedYear;
            
            $data = Cache::remember($cacheKey, now()->addDays(30), function () {
                try {
                    $response = Http::timeout(30)
                        ->retry(3, 100)
                        ->connectTimeout(10)
                        ->get('https://dayoffapi.vercel.app/api', [
                            'year' => $this->selectedYear
                        ]);

                    if (!$response->successful()) {
                        throw new \Exception('Gagal mengambil data hari libur');
                    }

                    $data = $response->json();
                    
                    // Simpan backup cache
                    Cache::put('holidays_' . $this->selectedYear . '_backup', $data, now()->addDays(90));
                    
                    return $data;
                } catch (\Exception $e) {
                    \Log::error('Holiday API Error: ' . $e->getMessage());
                    throw $e;
                }
            });

            if (empty($data)) {
                throw new \Exception('Data hari libur tidak tersedia');
            }

            // Merge dengan fallback data untuk melengkapi data yang kurang
            $fallbackData = $this->getFallbackData();
            $mergedData = $this->mergeHolidays($data, $fallbackData);
            
            $this->holidays = $mergedData;
            $this->generateCalendar();
            $this->loadUpcomingHolidays();

        } catch (\Exception $e) {
            $this->error = 'Tidak dapat mengambil data hari libur. Menampilkan data cache.';
            \Log::error('Holiday fetch error: ' . $e->getMessage());
            
            // Coba ambil dari backup cache
            $backupCache = Cache::get('holidays_' . $this->selectedYear . '_backup');
            $fallbackData = $this->getFallbackData();
            
            if ($backupCache) {
                $this->holidays = $this->mergeHolidays($backupCache, $fallbackData);
                $this->generateCalendar();
                $this->loadUpcomingHolidays();
                $this->error = 'Menampilkan data cache (mungkin tidak terbaru)';
            } else if (!empty($fallbackData)) {
                $this->holidays = $fallbackData;
                $this->generateCalendar();
                $this->loadUpcomingHolidays();
                $this->error = 'Menampilkan data offline';
            } else {
                $this->holidays = [];
                $this->error = 'Tidak ada data hari libur tersedia';
            }
        } finally {
            $this->isLoading = false;
        }
    }

    public function generateCalendar()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        // Mulai dari hari Minggu minggu pertama
        $startDate = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $endDate = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        
        $this->calendarDays = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-n-j');
            $isHoliday = false;
            $holidayInfo = null;
            
            // Cek apakah tanggal ini adalah hari libur
            foreach ($this->holidays as $holiday) {
                if ($holiday['tanggal'] === $dateString) {
                    $isHoliday = true;
                    $holidayInfo = $holiday;
                    break;
                }
            }
            
            $this->calendarDays[] = [
                'date' => $currentDate->copy(),
                'day' => $currentDate->day,
                'is_current_month' => $currentDate->month === $this->selectedMonth,
                'is_today' => $currentDate->isToday(),
                'is_weekend' => $currentDate->isWeekend(),
                'is_holiday' => $isHoliday,
                'holiday_info' => $holidayInfo,
            ];
            
            $currentDate->addDay();
        }
    }

    public function loadUpcomingHolidays()
    {
        try {
            $today = Carbon::today();
            $currentYear = now()->year;
            $nextYear = $currentYear + 1;
            
            // Ambil data tahun ini
            $cacheKey1 = 'holidays_' . $currentYear;
            $holidays1 = Cache::get($cacheKey1);
            
            // Jika tidak ada di cache, coba fetch atau gunakan backup
            if (!$holidays1) {
                $holidays1 = Cache::get('holidays_' . $currentYear . '_backup', []);
            }
            
            // Ambil data tahun depan
            $cacheKey2 = 'holidays_' . $nextYear;
            $holidays2 = Cache::get($cacheKey2);
            
            if (!$holidays2) {
                // Coba fetch tahun depan dengan timeout pendek
                try {
                    $response = Http::timeout(15)
                        ->retry(2, 100)
                        ->connectTimeout(5)
                        ->get('https://dayoffapi.vercel.app/api', ['year' => $nextYear]);
                    
                    if ($response->successful()) {
                        $holidays2 = $response->json();
                        Cache::put($cacheKey2, $holidays2, now()->addDays(30));
                        Cache::put('holidays_' . $nextYear . '_backup', $holidays2, now()->addDays(90));
                    } else {
                        $holidays2 = [];
                    }
                } catch (\Exception $e) {
                    $holidays2 = Cache::get('holidays_' . $nextYear . '_backup', []);
                }
            }

            $allHolidays = array_merge($holidays1 ?? [], $holidays2 ?? []);
            $upcoming = [];

            foreach ($allHolidays as $holiday) {
                try {
                    $holidayDate = Carbon::parse($holiday['tanggal']);
                    
                    if ($holidayDate->greaterThanOrEqualTo($today)) {
                        $holiday['days_until'] = $today->diffInDays($holidayDate);
                        $upcoming[] = $holiday;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Urutkan berdasarkan tanggal terdekat
            usort($upcoming, function($a, $b) {
                return $a['days_until'] <=> $b['days_until'];
            });

            $this->upcomingHolidays = array_slice($upcoming, 0, 5);
        } catch (\Exception $e) {
            \Log::error('Upcoming holidays error: ' . $e->getMessage());
            $this->upcomingHolidays = [];
        }
    }

    public function changeMonth($direction)
    {
        if ($direction === 'prev') {
            $this->selectedMonth--;
            if ($this->selectedMonth < 1) {
                $this->selectedMonth = 12;
                $this->selectedYear--;
                $this->fetchHolidays();
            } else {
                $this->generateCalendar();
            }
        } else {
            $this->selectedMonth++;
            if ($this->selectedMonth > 12) {
                $this->selectedMonth = 1;
                $this->selectedYear++;
                $this->fetchHolidays();
            } else {
                $this->generateCalendar();
            }
        }
    }

    public function goToToday()
    {
        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->month;
        
        if ($this->selectedYear != $this->currentYear) {
            $this->currentYear = $this->selectedYear;
            $this->fetchHolidays();
        } else {
            $this->generateCalendar();
        }
    }

    public function changeYear($year)
    {
        $this->selectedYear = $year;
        $this->currentYear = $year;
        $this->fetchHolidays();
    }

    public function refresh()
    {
        // Clear cache
        Cache::forget('holidays_' . $this->selectedYear);
        Cache::forget('holidays_' . $this->currentYear);
        Cache::forget('holidays_' . ($this->currentYear + 1));
        
        // Reload data
        $this->fetchHolidays();
        
        $this->dispatch('holiday-refreshed');
    }

    public function getMonthName()
    {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $monthNames[$this->selectedMonth] . ' ' . $this->selectedYear;
    }

    public function getHolidayStats()
    {
        $totalHolidays = count($this->holidays);
        $cutiCount = 0;
        $liburCount = 0;
        
        foreach ($this->holidays as $holiday) {
            if (isset($holiday['is_cuti']) && $holiday['is_cuti']) {
                $cutiCount++;
            } else {
                $liburCount++;
            }
        }
        
        return [
            'total' => $totalHolidays,
            'cuti' => $cutiCount,
            'libur' => $liburCount
        ];
    }

    private function mergeHolidays($apiData, $fallbackData)
    {
        // Buat array dengan key tanggal untuk cek duplikat
        $merged = [];
        
        // Tambahkan semua data dari API
        foreach ($apiData as $holiday) {
            $merged[$holiday['tanggal']] = $holiday;
        }
        
        // Tambahkan data fallback yang belum ada di API
        foreach ($fallbackData as $holiday) {
            if (!isset($merged[$holiday['tanggal']])) {
                $merged[$holiday['tanggal']] = $holiday;
            }
        }
        
        // Kembalikan sebagai array biasa (tanpa key)
        return array_values($merged);
    }

    private function getFallbackData()
    {
        // Data fallback untuk tahun 2026 (bisa ditambahkan tahun lainnya)
        $fallbackData = [
            2025 => [
                ['tanggal' => '2025-1-1', 'tanggal_display' => 'Rabu, 1 Januari 2025', 'keterangan' => 'Tahun Baru 2025', 'is_cuti' => false],
                ['tanggal' => '2025-1-29', 'tanggal_display' => 'Rabu, 29 Januari 2025', 'keterangan' => 'Tahun Baru Imlek 2576 Kongzili', 'is_cuti' => false],
                ['tanggal' => '2025-3-29', 'tanggal_display' => 'Sabtu, 29 Maret 2025', 'keterangan' => 'Hari Suci Nyepi Tahun Baru Saka 1947', 'is_cuti' => false],
                ['tanggal' => '2025-3-31', 'tanggal_display' => 'Senin, 31 Maret 2025', 'keterangan' => 'Isra Mikraj Nabi Muhammad', 'is_cuti' => false],
                ['tanggal' => '2025-4-18', 'tanggal_display' => 'Jumat, 18 April 2025', 'keterangan' => 'Wafat Yesus Kristus', 'is_cuti' => false],
                ['tanggal' => '2025-5-1', 'tanggal_display' => 'Kamis, 1 Mei 2025', 'keterangan' => 'Hari Buruh Internasional', 'is_cuti' => false],
                ['tanggal' => '2025-5-12', 'tanggal_display' => 'Senin, 12 Mei 2025', 'keterangan' => 'Kenaikan Yesus Kristus', 'is_cuti' => false],
                ['tanggal' => '2025-5-29', 'tanggal_display' => 'Kamis, 29 Mei 2025', 'keterangan' => 'Hari Raya Waisak 2569', 'is_cuti' => false],
                ['tanggal' => '2025-6-1', 'tanggal_display' => 'Minggu, 1 Juni 2025', 'keterangan' => 'Hari Lahir Pancasila', 'is_cuti' => false],
                ['tanggal' => '2025-8-17', 'tanggal_display' => 'Minggu, 17 Agustus 2025', 'keterangan' => 'Hari Kemerdekaan RI', 'is_cuti' => false],
                ['tanggal' => '2025-12-25', 'tanggal_display' => 'Kamis, 25 Desember 2025', 'keterangan' => 'Hari Raya Natal', 'is_cuti' => false],
            ],
            2026 => [
                ['tanggal' => '2026-1-1', 'tanggal_display' => 'Kamis, 1 Januari 2026', 'keterangan' => 'Tahun Baru 2026', 'is_cuti' => false],
                ['tanggal' => '2026-1-16', 'tanggal_display' => 'Jumat, 16 Januari 2026', 'keterangan' => 'Maulid Nabi Muhammad SAW', 'is_cuti' => false],
                ['tanggal' => '2026-2-17', 'tanggal_display' => 'Selasa, 17 Februari 2026', 'keterangan' => 'Tahun Baru Imlek 2577 Kongzili', 'is_cuti' => false],
                ['tanggal' => '2026-3-19', 'tanggal_display' => 'Kamis, 19 Maret 2026', 'keterangan' => 'Isra Mikraj Nabi Muhammad SAW', 'is_cuti' => false],
                ['tanggal' => '2026-3-20', 'tanggal_display' => 'Jumat, 20 Maret 2026', 'keterangan' => 'Hari Raya Nyepi Tahun Baru Saka 1948', 'is_cuti' => false],
                ['tanggal' => '2026-4-3', 'tanggal_display' => 'Jumat, 3 April 2026', 'keterangan' => 'Wafat Yesus Kristus', 'is_cuti' => false],
                ['tanggal' => '2026-5-1', 'tanggal_display' => 'Jumat, 1 Mei 2026', 'keterangan' => 'Hari Buruh Internasional', 'is_cuti' => false],
                ['tanggal' => '2026-5-14', 'tanggal_display' => 'Kamis, 14 Mei 2026', 'keterangan' => 'Kenaikan Yesus Kristus', 'is_cuti' => false],
                ['tanggal' => '2026-5-19', 'tanggal_display' => 'Selasa, 19 Mei 2026', 'keterangan' => 'Hari Raya Waisak 2570', 'is_cuti' => false],
                ['tanggal' => '2026-6-1', 'tanggal_display' => 'Senin, 1 Juni 2026', 'keterangan' => 'Hari Lahir Pancasila', 'is_cuti' => false],
                ['tanggal' => '2026-8-17', 'tanggal_display' => 'Senin, 17 Agustus 2026', 'keterangan' => 'Hari Kemerdekaan RI', 'is_cuti' => false],
                ['tanggal' => '2026-12-25', 'tanggal_display' => 'Jumat, 25 Desember 2026', 'keterangan' => 'Hari Raya Natal', 'is_cuti' => false],
            ],
        ];

        return $fallbackData[$this->selectedYear] ?? [];
    }

    public function render()
    {
        $stats = $this->getHolidayStats();
        
        return view('livewire.holiday-calendar', [
            'monthName' => $this->getMonthName(),
            'stats' => $stats
        ]);
    }
}