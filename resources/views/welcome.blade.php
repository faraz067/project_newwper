<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* CSS bawaan Laravel (Tailwind v4) tetap dipertahankan agar layout tidak rusak */
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */@layer theme{:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-serif:ui-serif,Georgia,Cambria,"Times New Roman",Times,serif;--font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;--color-red-500:oklch(.637 .237 25.331);--color-red-600:oklch(.577 .245 27.325);--color-white:#fff;--spacing:.25rem;--text-sm:.875rem;--text-base:1rem;--text-lg:1.125rem;--text-xl:1.25rem;--font-weight-medium:500;--font-weight-semibold:600;--radius-lg:.5rem;--radius-xl:.75rem;--shadow-sm:0 1px 3px 0 #0000001a,0 1px 2px -1px #0000001a;--default-transition-duration:.15s;}}@layer base{*,:after,:before,::backdrop{box-sizing:border-box;border:0 solid;margin:0;padding:0}html,:host{-webkit-text-size-adjust:100%;tab-size:4;line-height:1.5;font-family:var(--font-sans)}body{line-height:inherit}a{color:inherit;text-decoration:inherit}h1,h2,h3{font-weight:inherit}img,svg{vertical-align:middle;display:block}button{font:inherit;color:inherit;background-color:#0000}}@layer utilities{.absolute{position:absolute}.relative{position:relative}.inset-0{inset:0}.flex{display:flex}.hidden{display:none}.grid{display:grid}.h-full{height:100%}.w-full{width:100%}.max-w-\[1200px\]{max-width:1200px}.flex-col{flex-direction:column}.items-center{align-items:center}.justify-center{justify-content:center}.justify-between{justify-content:space-between}.gap-4{gap:1rem}.gap-6{gap:1.5rem}.rounded-lg{border-radius:var(--radius-lg)}.rounded-xl{border-radius:var(--radius-xl)}.bg-white{background-color:var(--color-white)}.p-6{padding:1.5rem}.px-4{padding-left:1rem;padding-right:1rem}.py-2{padding-top:.5rem;padding-bottom:.5rem}.text-center{text-align:center}.text-sm{font-size:var(--text-sm)}.text-xl{font-size:var(--text-xl)}.font-medium{font-weight:var(--font-weight-medium)}.font-semibold{font-weight:var(--font-weight-semibold)}.text-gray-500{color:#6b7280}.text-gray-900{color:#111827}.transition{transition-property:all;transition-timing-function:cubic-bezier(.4,0,.2,1);transition-duration:var(--default-transition-duration)}.hover\:text-black:hover{color:#000}.dark\:bg-gray-900{background-color:#111827}.dark\:text-white{color:#fff}.dark\:text-gray-400{color:#9ca3af}.lg\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}.lg\:flex-row{flex-direction:row}.lg\:p-12{padding:3rem}}
                /* Tambahan Custom CSS Sedikit untuk mempercantik */
                .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0,0,0,0.05); }
                .dark .glass-card { background: rgba(20, 20, 20, 0.7); border: 1px solid rgba(255,255,255,0.05); }
                .gradient-bg { background: linear-gradient(135deg, #FF2D20 0%, #ff6b6b 100%); }
            </style>
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col min-h-screen font-sans antialiased selection:bg-[#FF2D20] selection:text-white">
        
        <header class="w-full max-w-[1200px] mx-auto p-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 bg-[#FF2D20] rounded-lg flex items-center justify-center text-white font-bold">L</div>
                <span class="font-semibold text-lg dark:text-white">LaravelApp</span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-[#1f1f1f] dark:text-white dark:hover:bg-[#2f2f2f] transition text-sm font-medium">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-black dark:text-[#EDEDEC] dark:hover:text-white transition">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-[#FF2D20] text-white hover:bg-[#e0261a] transition text-sm font-medium shadow-sm">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="flex-grow flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-[1100px] grid lg:grid-cols-2 gap-6 lg:gap-12 items-center">
        <div class="flex flex-col gap-6 text-center lg:text-left">
            <div class="space-y-4">
                <h1 class="text-4xl lg:text-6xl font-bold tracking-tight dark:text-white">
                    Bangun Ide <br>
                    <span class="text-[#FF2D20]">Menjadi Nyata.</span>
                </h1>
                <p class="text-lg text-gray-500 dark:text-gray-400">
                    Pesan lapangan olahraga favoritmu dengan mudah dan cepat melalui platform kami.
                </p>
            </div>
        </div>
        
        <div class="hidden lg:block">
            <div class="gradient-bg w-full h-[300px] rounded-3xl shadow-xl flex items-center justify-center text-white text-2xl font-bold">
                Cari Lapangan Terbaik
            </div>
        </div>
    </div>

    <div class="w-full max-w-[1100px] mt-20">
        <h2 class="text-2xl font-bold mb-8 dark:text-white">Pilih Lapangan Tersedia</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($courts as $court)
                <div class="glass-card rounded-2xl overflow-hidden flex flex-col transition hover:scale-[1.02] duration-300">
                    <div class="relative h-48 w-full">
                        <img src="{{ asset('storage/' . $court->photo) }}" 
                             alt="{{ $court->name }}" 
                             class="w-full h-full object-cover">
                        
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ $court->status == 'tersedia' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                                {{ strtoupper($court->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold dark:text-white">{{ $court->name }}</h3>
                            <span class="text-xs font-medium px-2 py-1 bg-gray-100 dark:bg-gray-800 dark:text-gray-300 rounded">
                                {{ $court->type }}
                            </span>
                        </div>
                        
                        <p class="text-2xl font-bold text-[#FF2D20] mt-auto">
                            Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} <span class="text-sm text-gray-500 font-normal">/jam</span>
                        </p>

                        <a href="#" class="mt-4 w-full py-3 bg-[#FF2D20] text-white text-center rounded-xl font-semibold hover:bg-[#e0261a] transition">
                            Booking Sekarang
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-500">Belum ada data lapangan yang tersedia.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $courts->links() }}
        </div>
    </div>
</main>