<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class FunFact extends Component
{
    public $fact = '';
    public $loading = false;
    public $error = '';

    public function mount()
    {
        $this->getFact();
    }

    public function getFact()
    {
        $this->loading = true;
        $this->error = '';

        try {
            $response = Http::timeout(10)->get('https://uselessfacts.jsph.pl/api/v2/facts/random');

            if ($response->successful()) {
                $data = $response->json();
                $this->fact = $data['text'] ?? 'No fact available';
            } else {
                $this->error = 'Failed to fetch fact. Please try again.';
            }
        } catch (\Exception $e) {
            $this->error = 'An error occurred: ' . $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.fun-fact');
    }
}