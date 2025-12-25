<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'], // Cek unik di tabel customers
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_wa' => ['required', 'string'], // Tambahan kolom
            'alamat' => ['required', 'string'], // Tambahan kolom
        ]);

        $customer = Customer::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
            'description' => 'Customer Baru Register Online', // Default value
        ]);

        event(new Registered($customer));

        Auth::guard('customer')->login($customer);

        return redirect(route('home', absolute: false));
    }
}
