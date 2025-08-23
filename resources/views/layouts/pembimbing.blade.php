<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembimbing - @yield('title', 'Sistem')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Responsive Styles */
        @media (max-width: 768px) {
            .flex {
                flex-direction: column;
            }
            aside.w-64 {
                width: 100%;
                min-height: auto;
                margin-bottom: 1rem;
            }
            .flex-1 {
                width: 100%;
                padding: 1rem;
            }
            .space-y-2 {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: center;
            }
            .space-y-2 li {
                width: calc(50% - 0.5rem);
            }
            .space-y-2 a {
                width: 100%;
                text-align: center;
            }
        }
        
        @media (max-width: 576px) {
            .space-y-2 li {
                width: 100%;
            }
            .w-16 {
                width: 3rem;
            }
            .text-sm {
                font-size: 0.8rem;
            }
            .px-3 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .py-2 {
                padding-top: 0.375rem;
                padding-bottom: 0.375rem;
            }
        }
    </style>
</head>
<body class="bg-white-100 font-sans leading-normal tracking-normal">

    <div class="flex">
        {{-- Sidebar --}}
        <aside class="w-64 min-h-screen bg-white shadow-md p-4 flex flex-col justify-between">
            <div>
                <div class="mb-6 text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 mx-auto mb-2">
                    <h1 class="font-bold text-sm leading-tight text-center">PEMERINTAH KOTA <br><span class="text-red-600">BUKITTINGGI</span></h1>
                    <p class="text-xs text-gray-600 mt-1">Provinsi Sumatera Barat</p>
                </div>

                <ul class="space-y-2 font-medium text-sm text-center">
                    <li>
                        <a href="{{ route('pembimbing.dashboard') }}"
                           class="block px-3 py-2 rounded-full {{ request()->routeIs('pembimbing.dashboard') ? 'text-white bg-blue-600' : 'hover:bg-gray-100' }}">
                           Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pembimbing.absensi') }}"
                           class="block px-3 py-2 rounded-full {{ request()->routeIs('pembimbing.absensi') ? 'text-white bg-blue-600' : 'hover:bg-gray-100' }}">
                           Absensi Mahasiswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pembimbing.logbook') }}"
                           class="block px-3 py-2 rounded-full {{ request()->routeIs('pembimbing.logbook') ? 'text-white bg-blue-600' : 'hover:bg-gray-100' }}">
                           Logbook Mahasiswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pembimbing.laporan') }}"
                           class="block px-3 py-2 rounded-full {{ request()->routeIs('pembimbing.laporan') ? 'text-white bg-blue-600' : 'hover:bg-gray-100' }}">
                           Laporan Akhir
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-center py-3 text-white font-medium bg-blue-600 rounded-lg mt-4 hover:bg-blue-700">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 bg-gray-100">
            @yield('content')
        </div>
    </div>

</body>
</html>