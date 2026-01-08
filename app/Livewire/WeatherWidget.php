<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherWidget extends Component
{
    public $weatherData = null;
    public $location = null;
    public $currentWeather = null;
    public $forecast = [];
    public $isLoading = true;
    public $error = null;

    public $areaCode = '32.04.08.2002'; 
    public $showLocationPicker = false;
    public $popularAreas = [];
    public $searchQuery = '';
    public $filteredAreas = [];

    public function mount()
    {
        // Load saved location from session
        $savedAreaCode = session('weather_area_code', '32.04.08.2002');
        $this->areaCode = $savedAreaCode;
        
        $this->loadPopularAreas();
        $this->fetchWeatherData();
    }

    public function loadPopularAreas()
    {
        $this->popularAreas = [

            // KECAMATAN BOJONGSOANG
            '32.04.08.2001' => 'Kec. Bojongsoang - Desa Lengkong',
            '32.04.08.2002' => 'Kec. Bojongsoang - Desa Bojongsoang',
            '32.04.08.2003' => 'Kec. Bojongsoang - Desa Buahbatu',
            '32.04.08.2004' => 'Kec. Bojongsoang - Desa Cipagalo',
            '32.04.08.2005' => 'Kec. Bojongsoang - Desa Bojongsari',
            '32.04.08.2006' => 'Kec. Bojongsoang - Desa Tegalluar',

            // KECAMATAN DAYEUHKOLOT
            '32.04.12.1001' => 'Kec. Dayeuhkolot - Kel. Pasawahan',
            '32.04.12.2003' => 'Kec. Dayeuhkolot - Desa Cangkuang Wetan',
            '32.04.12.2004' => 'Kec. Dayeuhkolot - Desa Cangkuang Kulon',
            '32.04.12.2002' => 'Kec. Dayeuhkolot - Desa Dayeuhkolot',
            '32.04.12.2005' => 'Kec. Dayeuhkolot - Desa Sukapura',
            '32.04.12.2006' => 'Kec. Dayeuhkolot - Desa Citeureup',

        ];


        $this->filteredAreas = $this->popularAreas;
    }

    public function updatedSearchQuery()
    {
        if (empty($this->searchQuery)) {
            $this->filteredAreas = $this->popularAreas;
            return;
        }

        $query = strtolower($this->searchQuery);
        
        $this->filteredAreas = array_filter($this->popularAreas, function($name) use ($query) {
            return str_contains(strtolower($name), $query);
        });
    }

    public function toggleLocationPicker()
    {
        $this->showLocationPicker = !$this->showLocationPicker;
        
        if (!$this->showLocationPicker) {
            $this->searchQuery = '';
            $this->filteredAreas = $this->popularAreas;
        }
    }

    public function selectPopularArea($code)
    {
        $this->areaCode = $code;
        $this->showLocationPicker = false;
        $this->searchQuery = '';
        
        // Save to session
        session(['weather_area_code' => $code]);
        
        // Clear cache untuk lokasi ini dan fetch data baru
        Cache::forget('bmkg_weather_' . $code);
        $this->fetchWeatherData();
    }

    public function fetchWeatherData()
    {
        $this->isLoading = true;
        $this->error = null;

        try {
            // Cache data selama 30 menit untuk mengurangi request ke API
            $cacheKey = 'bmkg_weather_' . $this->areaCode;
            
            $data = Cache::remember($cacheKey, now()->addMinutes(30), function () {
                $response = Http::timeout(10)->get('https://api.bmkg.go.id/publik/prakiraan-cuaca', [
                    'adm4' => $this->areaCode
                ]);

                if (!$response->successful()) {
                    throw new \Exception('Gagal mengambil data dari BMKG');
                }

                return $response->json();
            });

            if (!$data) {
                throw new \Exception('Data cuaca tidak tersedia');
            }

            $this->weatherData = $data;
            $this->processWeatherData($data);

        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        } finally {
            $this->isLoading = false;
        }
    }

    private function processWeatherData($data)
    {
        // Extract location info
        if (isset($data['lokasi'])) {
            $this->location = [
                'desa' => $data['lokasi']['desa'] ?? 'N/A',
                'kecamatan' => $data['lokasi']['kecamatan'] ?? 'N/A',
                'kotkab' => $data['lokasi']['kotkab'] ?? 'N/A',
                'provinsi' => $data['lokasi']['provinsi'] ?? 'N/A',
                'lat' => $data['lokasi']['lat'] ?? null,
                'lon' => $data['lokasi']['lon'] ?? null,
                'timezone' => $data['lokasi']['timezone'] ?? 'Asia/Jakarta',
            ];
        }

        // Extract current weather (first available data point)
        if (isset($data['data'][0]['cuaca'][0][0])) {
            $current = $data['data'][0]['cuaca'][0][0];
            $this->currentWeather = [
                'datetime' => $current['local_datetime'] ?? now()->format('Y-m-d H:i:s'),
                'weather_desc' => $current['weather_desc'] ?? 'N/A',
                'weather_desc_en' => $current['weather_desc_en'] ?? 'N/A',
                'image' => $current['image'] ?? null,
                'temperature' => $current['t'] ?? 0,
                'humidity' => $current['hu'] ?? 0,
                'wind_speed' => $current['ws'] ?? 0,
                'wind_direction' => $current['wd'] ?? 'N/A',
                'visibility' => $current['vs_text'] ?? 'N/A',
            ];
        }

        // Extract forecast for next 3 days
        if (isset($data['data'][0]['cuaca']) && is_array($data['data'][0]['cuaca'])) {
            $this->forecast = [];
            
            // Ambil maksimal 3 hari
            $days = array_slice($data['data'][0]['cuaca'], 0, 3);
            
            foreach ($days as $dayIndex => $hourlyData) {
                if (is_array($hourlyData) && !empty($hourlyData)) {
                    // Ambil data pagi (jam 07:00), siang (13:00), dan malam (19:00)
                    $morningData = $this->findWeatherByTime($hourlyData, '07:00') ?? $hourlyData[0];
                    $noonData = $this->findWeatherByTime($hourlyData, '13:00') ?? $hourlyData[count($hourlyData) > 1 ? 1 : 0];
                    $nightData = $this->findWeatherByTime($hourlyData, '19:00') ?? end($hourlyData);
                    
                    $this->forecast[] = [
                        'day' => $dayIndex,
                        'date' => $this->extractDate($morningData['local_datetime'] ?? now()->addDays($dayIndex)->format('Y-m-d')),
                        'morning' => $this->formatWeatherData($morningData),
                        'noon' => $this->formatWeatherData($noonData),
                        'night' => $this->formatWeatherData($nightData),
                    ];
                }
            }
        }
    }

    private function findWeatherByTime($hourlyData, $targetTime)
    {
        foreach ($hourlyData as $data) {
            if (isset($data['local_datetime'])) {
                $time = date('H:i', strtotime($data['local_datetime']));
                if ($time === $targetTime) {
                    return $data;
                }
            }
        }
        return null;
    }

    private function formatWeatherData($data)
    {
        return [
            'weather_desc' => $data['weather_desc'] ?? 'N/A',
            'image' => $data['image'] ?? null,
            'temperature' => $data['t'] ?? 0,
            'humidity' => $data['hu'] ?? 0,
        ];
    }

    private function extractDate($datetime)
    {
        return date('Y-m-d', strtotime($datetime));
    }

    public function refresh()
    {
        // Clear cache dan fetch ulang
        Cache::forget('bmkg_weather_' . $this->areaCode);
        $this->fetchWeatherData();
        
        $this->dispatch('weather-refreshed');
    }

    public function render()
    {
        return view('livewire.weather-widget');
    }
}