<?php

namespace App\Http\Controllers;

use App\Models\Service; // Pastikan Anda mengimpor model Service

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();

        // Meneruskan data layanan ke view 'home'
        return view('home', compact('services'));
    }
}
