@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

    {{-- Hero Section   --}}
    <div class="grid px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        <div class="mr-auto place-self-center lg:col-span-7">
            <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight md:text-5xl xl:text-6xl dark:text-white">
                Laundry Bersih, Wangi, dan Tepat Waktu</h1>
            <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">Kami melayani
                cuci, setrika, dan perawatan pakaian dengan hasil rapi dan higienis, siap dipakai tanpa ribet.</p>
            <a href="{{ route('customer_menu') }}"
                class="mb-4 sm:mb-0 inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                Jemput Cucian Kamu
                <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </a>
            <a href="/#contact"
                class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                Hubungi Kami
            </a>
        </div>
        <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
            <img src="{{ asset('images/logo_main.svg') }}" alt="mockup">
        </div>
    </div>

    {{-- Services --}}
    <div id="price-list" class="mx-auto text-center mb-8 max-w-screen-sm lg:mb-16">
        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Layanan Kami</h2>
        <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">Kami menyediakan layanan laundry yang dirancang
            untuk membantu pengguna dalam mengelola proses pencucian pakaian secara efisien dan terorganisir..
        </p>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="services-list">
        @forelse ($services as $service)
            <div
                class="block p-6 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">

                <a href="#">
                    <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                        {{ $service->nama_paket }}</h5>
                </a>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $service->deskripsi }}</p>
                <p class="mb-3 font-bold text-lg text-primary-700 dark:text-primary-500">Rp
                    {{ number_format($service->harga, 0, ',', '.') }}</p>
            </div>
        @empty
            <p class="col-span-full text-gray-500 dark:text-gray-400">Tidak ada layanan yang tersedia saat ini.</p>
        @endforelse
    </div>

    {{-- Quotes --}}
    <div class="max-w-7xl
    px-4 py-8 mx-auto text-center lg:py-16 lg:px-6">
        <figure class="max-w-screen-md mx-auto">
            <blockquote>
                @livewire('fun-fact')
            </blockquote>
        </figure>
    </div>

    {{-- Team --}}
    <div id="about-us" class="py-8 px-4 mx-auto text-center lg:py-16 lg:px-6">
        <div class="mx-auto mb-8 max-w-screen-sm lg:mb-16">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Tentang Kami</h2>
            <p class="font-light text-gray-500 sm:text-xl dark:text-gray-400">Aplikasi kasir dan tracking berbasis web ini
                dirancang untuk menertibkan manajemen usaha laundry melalui pencatatan transaksi dan pemantauan status
                pesanan secara real-time. Proyek ini dikembangkan oleh tim mahasiswa
                Program Studi Sistem Informasi sebagai bagian dari Tugas Besar mata kuliah <b>Pengembangan Aplikasi Web</b>.
            </p>
        </div>
        <div class="grid gap-8 lg:gap-16 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
            <x-profile-card image="images/profile/akhdan.jpg" name="Itsna Akhdan Fadhil" role="Ketua Kelompok"
                github-url="https://github.com/EsGoreng/" instagram-url="https://www.instagram.com/_ahdn/" />
            <x-profile-card image="images/profile/intan.jpeg" name="Intan Nurlistiyani" role="Anggota Kelompok"
                github-url="https://github.com/intanrls" instagram-url="https://www.instagram.com/lntanrs/" />
            <x-profile-card image="images/profile/hadi.jpeg" name="Hadi Dwi Pranoto" role="Anggota Kelompok"
                github-url="https://github.com/7Serender" instagram-url="https://www.instagram.com/hddwp/" />
        </div>
    </div>

    {{-- Contact Us --}}
    <div id="contact" class="py-8 lg:py-16 px-4 mx-auto max-w-3xl">
        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900 dark:text-white">Hubungi Kami</h2>
        <p class="mb-8 lg:mb-16 font-light text-center text-gray-500 dark:text-gray-400 sm:text-xl">Jika Anda memiliki
            pertanyaan, saran, atau masukan terkait sistem yang dikembangkan, silakan menghubungi kami melalui informasi
            kontak yang tersedia. Kami terbuka terhadap umpan balik untuk pengembangan dan penyempurnaan sistem ke depannya.
        </p>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                role="alert">
                <span class="font-medium">Sukses!</span> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                role="alert">
                <span class="font-medium">Oops! Ada yang salah.</span>
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contact.send') }}" method="POST" class="space-y-8">
            @csrf
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your
                    email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light"
                    placeholder="name@email.com" required>
            </div>
            <div>
                <label for="subject"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Subject</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                    class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light"
                    placeholder="Let us know how we can help you" required>
            </div>
            <div class="sm:col-span-2">
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Your
                    message</label>
                <textarea id="message" name="message" rows="6"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg shadow-sm border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Leave a comment...">{{ old('message') }}</textarea>
            </div>
            <button type="submit"
                class="py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-primary-700 sm:w-fit hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Kirim
                Pesan</button>
        </form>
    </div>

@endsection
