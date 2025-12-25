<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('auth:web,customer')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Area ADMIN / OWNER (Guard: web) ---
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.main');
    })->name('dashboard');


    // Masukkan route manajemen order, user, dll di sini
});


// --- Area CUSTOMER (Guard: customer) ---
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer-menu', function () {
        return view('dashboard.customer'); // Buat view baru untuk customer
    })->name('customer_menu');

    // Masukkan route buat pesanan, cek history order di sini
});

require __DIR__ . '/auth.php';
