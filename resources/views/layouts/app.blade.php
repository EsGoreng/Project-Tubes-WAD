<!DOCTYPE html>
<html lang="en" class="h-ful bg-gray-100 dark:bg-gray-950 scheme-light dark:scheme-dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    @livewireStyles
    @filamentStyles
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/logo_mascot.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</head>

<body class="h-full">
    <div class="min-h-full">
        <nav class="bg-gray-800 dark:bg-gray-800/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <img src="{{ asset('images/logo_title.svg') }}" alt="SiBersih" class="size-32" />
                        </div>
                        <div class="hidden md:block">
                            @php
                                $isCustomer = Auth::guard('customer')->check();
                                $isAdmin = Auth::guard('web')->check();
                            @endphp
                            <div class="ml-10 flex items-baseline space-x-4">
                                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                                    Home
                                </x-nav-link>
                                <x-nav-link href="/#price-list" :active="request()->routeIs('pricelist')">
                                    Our Services
                                </x-nav-link>
                                <x-nav-link href="/#terms-and-conditions" :active="request()->routeIs('termsandconditions')">
                                    TnC
                                </x-nav-link>

                                <x-nav-link href="/#contact" :active="request()->routeIs('contact')">
                                    Contact Us
                                </x-nav-link>

                                @if ($isCustomer)
                                    <x-nav-link :href="route('customer_menu')" :active="request()->routeIs('customer_menu')">
                                        Check Laundry
                                    </x-nav-link>
                                @endif
                                @if ($isAdmin)
                                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                        Dashboard
                                    </x-nav-link>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        @php
                            $currentUser = Auth::guard('web')->user() ?? Auth::guard('customer')->user();
                            $isCustomer = Auth::guard('customer')->check();
                            $isAdmin = Auth::guard('web')->check();
                        @endphp

                        <div class="ml-4 flex items-center md:ml-6">

                            @if ($currentUser)

                                <div class="text-sm text-gray-300 mr-2">
                                    {{ $currentUser->nama_lengkap }}
                                    <span class="text-xs text-gray-500 block text-right">
                                        {{ $isAdmin ? '(Admin)' : '(Customer)' }}
                                    </span>
                                </div>

                                <el-dropdown class="relative ml-4">
                                    <button
                                        class="relative flex max-w-xs items-center rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                                        <span class="absolute -inset-1.5"></span>
                                        <span class="sr-only">Open user menu</span>
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($currentUser->nama_lengkap) }}&background=random"
                                            alt=""
                                            class="size-8 rounded-full outline -outline-offset-1 outline-white/10" />
                                    </button>

                                    <el-menu anchor="bottom end" popover
                                        class="w-48 origin-top-right rounded-md bg-white py-1 shadow-lg outline-1 outline-black/5 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">

                                        @if ($isAdmin)
                                            <a href="{{ route('dashboard') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:focus:bg-white/5">
                                                Admin Dashboard
                                            </a>
                                            <a href="{{ route('profile.edit') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:focus:bg-white/5">
                                                Your
                                                Profile
                                            </a>
                                        @endif

                                        @if ($isCustomer)
                                            <a href="{{ route('customer_menu') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:focus:bg-white/5">
                                                Check Laundry
                                            </a>
                                            <a href="{{ route('profile.edit') }}"
                                                class="block px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:focus:bg-white/5">
                                                Your
                                                Profile
                                            </a>
                                        @endif

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); this.closest('form').submit();"
                                                class="block px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:outline-hidden dark:text-gray-300 dark:focus:bg-white/5">
                                                Sign out
                                            </a>
                                        </form>
                                    </el-menu>
                                </el-dropdown>
                            @else
                                {{-- Jika Belum Login (GUEST) --}}

                                <a href="{{ route('login') }}"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                                        Register
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <button type="button" command="--toggle" commandfor="mobile-menu"
                            class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
                            <span class="absolute -inset-0.5"></span>
                            <span class="sr-only">Open main menu</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                data-slot="icon" aria-hidden="true" class="size-6 in-aria-expanded:hidden">
                                <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                data-slot="icon" aria-hidden="true" class="size-6 not-in-aria-expanded:hidden">
                                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <el-disclosure id="mobile-menu" hidden class="block md:hidden">
                @php
                    $currentUser = Auth::guard('web')->user() ?? Auth::guard('customer')->user();
                    $isAdmin = Auth::guard('web')->check();
                    $isCustomer = Auth::guard('customer')->check();
                @endphp

                <div class="flex flex-wrap space-y-1 px-2 pt-2 pb-3 sm:px-3 gap-y-2">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Home
                    </x-nav-link>
                    <x-nav-link href="/#price-list" :active="request()->routeIs('pricelist')">
                        Our Services
                    </x-nav-link>
                    <x-nav-link href="/#terms-and-condition" :active="request()->routeIs('termsandconditions')">
                        TnC
                    </x-nav-link>

                    <x-nav-link href="#contact" :active="request()->routeIs('contact')">
                        Contact Us
                    </x-nav-link>
                    @if ($isCustomer)
                        <x-nav-link :href="route('customer_menu')" :active="request()->routeIs('customer_menu')">
                            Check Laundry
                        </x-nav-link>
                    @endif
                    @if ($isAdmin)
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            Dashboard
                        </x-nav-link>
                    @endif
                </div>

                <div class="border-t border-white/10 pt-4 pb-3">
                    @if ($currentUser)
                        <div class="flex items-center px-5">
                            <div class="shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($currentUser->nama_lengkap) }}&background=random"
                                    class="size-10 rounded-full outline -outline-offset-1 outline-white/10" />
                            </div>
                            <div class="ml-3">
                                <div class="text-base/5 font-medium text-white">{{ $currentUser->nama_lengkap }}</div>
                                <div class="text-sm font-medium text-gray-400">{{ $currentUser->email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1 px-2">
                            @if ($isAdmin)
                                <a href="{{ route('profile.edit') }}"
                                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Your
                                    Profile</a>

                                <a href="{{ route('dashboard') }}"
                                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Dashboard</a>
                            @endif
                            @if ($isCustomer)
                                <a href="{{ route('profile.edit') }}"
                                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Your
                                    Profile</a>
                                <a href="{{ route('customer_menu') }}"
                                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Cek
                                    Status Cucian</a>
                            @endif

                            {{-- LOGOUT --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                                    Sign out
                                </a>
                            </form>
                        </div>
                    @else
                        {{-- Tampilan Mobile untuk Guest --}}
                        <div class="mt-3 space-y-1 px-2">
                            <a href="{{ route('login') }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Log
                                in</a>
                            <a href="{{ route('register') }}"
                                class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Register</a>
                        </div>
                    @endif
                </div>
            </el-disclosure>
        </nav>

        <x-header :hide="request()->routeIs('home')"
            class="relative bg-white shadow-sm dark:bg-gray-800 dark:shadow-none dark:after:pointer-events-none dark:after:absolute dark:after:inset-x-0 dark:after:inset-y-0 dark:after:border-y dark:after:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">@yield('title')</h1>
            </div>
        </x-header>

        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                @livewire('notifications')
                @yield('content')
            </div>  
        </main>
    </div>

    <x-footer></x-footer>
    @filamentScripts
    @livewireScripts
</body>

</html>
