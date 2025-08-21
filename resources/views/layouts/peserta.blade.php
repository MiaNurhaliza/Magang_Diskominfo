<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title', 'Sistem')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                        <a href="{{ route('peserta.dashboard') }}"
                           class="block px-3 py-2 rounded-full {{ request()->routeIs('peserta.dashboard') ? 'text-white bg-blue-600' : 'hover:bg-gray-100' }}">
                           Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('absensi.index') }}"
                           class="block px-3 py-2 rounded-full {{ request()->routeIs('absensi.index') ? 'text-white bg-blue-600' : 'hover:bg-gray-100' }}">
                           Absensi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('upload_logbook_harian') }}"
                        class="block px-3 py-2 rounded-full {{ request()->routeIs('upload_logbook_harian') ? 'text-white bg-blue-600': 'hover:bg-gray-100'}}">
                        Upload Logbook Harian
                        </a>
                    </li>
                    <li>
                        <a href="{{route('upload_laporan_akhir')}}"
                           class="block px-3 py-2 rounded-full {{ request()->routeIs('upload_laporan_akhir') ? 'text-white bg-blue-600' : 'hover:bg-gray-100' }}">
                           Upload Laporan Akhir
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('unduh_sertifikat') }}"
                           class="block px-3 py-2 rounded-full {{ request()->routeIs('unduh_sertifikat') ? 'text-white bg-blue-600' : 'hover:bg-gray-100' }}">
                           Unduh Sertifikat
                        </a>
                    </li>
                    
                </ul>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-center py-3 text-white font-medium bg-blue-600 rounded-lg mt-4 hover:bg-blue-700 transition duration-200">Keluar</button>
                </form>
            </div>
        </aside>

        {{-- Content --}}
        <div class="flex-1 p-6">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
