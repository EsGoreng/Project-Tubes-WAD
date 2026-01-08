<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/contact', [HomeController::class, 'sendContactEmail'])->name('contact.send');


Route::middleware('auth:web,customer')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.main');
    })->name('dashboard');

});

Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer-menu', function () {
        return view('dashboard.customer');
    })->name('customer_menu');
});

require __DIR__ . '/auth.php';
