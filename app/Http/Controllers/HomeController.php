<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();

        return view('home', compact('services'));
    }

    public function sendContactEmail(Request $request)
    {
        $details = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|min:5',
            'message' => 'required|min:10',
        ]);

        Mail::to('uramazingdev@gmail.com')->send(new ContactFormMail($details));

        return back()->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan segera merespons.');
    }
}
