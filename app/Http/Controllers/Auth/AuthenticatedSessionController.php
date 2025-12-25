<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dasar
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Login sebagai ADMIN (User)
        // Menggunakan guard 'web' (default laravel untuk tabel users)
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // Redirect Admin ke dashboard Admin
            return redirect()->intended('/dashboard');
        }

        // 3. Jika gagal Admin, Coba Login sebagai CUSTOMER
        // Menggunakan guard 'customer' (untuk tabel customers)
        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // Redirect Customer ke dashboard Customer
            return redirect()->intended('/');
        }

        // 4. Jika keduanya gagal, kembalikan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        // 1. Cek guard 'web' (Admin), jika login, maka logout.
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        }

        // 3. Hancurkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
