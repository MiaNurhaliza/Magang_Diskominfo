@extends('layouts.backend')

@section('title', 'Dashboard Admin')

@section('content')
   
<main class="flex-1 bg-white-100 p-8">
    <h1 class="text-xl font-bold mb-6 ">Selamat Datang, Admin</h1>

    {{-- Card area abu --}}
            <div class="bg-gray-100 p-6 rounded-lg">
                {{-- Statistik --}}
                <div class="grid grid-cols-3 gap-4 mb-6">
                    @php
                        $statistik = [
                            'Jumlah Pendaftar',
                            'Jumlah Pendaftar Diterima',
                            'Jumlah Pendaftar Ditolak',
                            'Jumlah Peserta Magang',
                            'Jumlah Laporan Akhir',
                            'Jumlah Sertifikat yang telah diterbitkan'
                        ];
                    @endphp
                    @foreach ($statistik as $item)
                    <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600">{{ $item }}</p>
                            <h2 class="text-2xl font-bold mt-1">10</h2>
                        </div>
                        <div class="bg-blue-600 text-white p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.779.657 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>


    {{-- Aktivitas Hari Ini --}}
    <div class="bg-white shadow rounded p-4">
        <h3 class="text-lg font-semibold mb-4">Aktifitas Hari ini</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Nama Peserta</th>
                        <th class="px-4 py-2 text-left">Sekolah / Perguruan tinggi</th>
                        <th class="px-4 py-2 text-left">Absen</th>
                        <th class="px-4 py-2 text-left">Logbook</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">1</td>
                        <td class="border px-4 py-2">Marion Jola</td>
                        <td class="border px-4 py-2">Sma 1</td>
                        <td class="border px-4 py-2 text-green-600 font-semibold">Hadir</td>
                        <td class="border px-4 py-2 text-green-600 font-semibold">Masuk</td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">2</td>
                        <td class="border px-4 py-2">Siti Aisyah</td>
                        <td class="border px-4 py-2">Sma 2</td>
                        <td class="border px-4 py-2 text-red-500 font-semibold">Sakit</td>
                        <td class="border px-4 py-2 text-red-500 font-semibold">Belum</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection