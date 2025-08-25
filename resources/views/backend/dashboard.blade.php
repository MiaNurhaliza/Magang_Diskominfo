@extends('layouts.backend')

@section('title', 'Dashboard Admin')

@section('content')
   
<main class="flex-1 bg-white-100 p-8">
    <h1 class="text-xl font-bold mb-6 ">Selamat Datang, Admin</h1>

    {{-- Card area abu --}}
            <div class="bg-gray-100 p-6 rounded-lg">
                {{-- Statistik --}}
                <div class="grid grid-cols-3 gap-4 mb-6">
                    {{-- Jumlah Pendaftar --}}
                    <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Pendaftar</p>
                            <h2 class="text-2xl font-bold mt-1">{{ $totalPendaftar }}</h2>
                        </div>
                        <div class="bg-blue-600 text-white p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.779.657 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Jumlah Pendaftar Diterima --}}
                    <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Pendaftar Diterima</p>
                            <h2 class="text-2xl font-bold mt-1">{{ $diterima }}</h2>
                        </div>
                        <div class="bg-blue-600 text-white p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Jumlah Pendaftar Ditolak --}}
                    <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Pendaftar Ditolak</p>
                            <h2 class="text-2xl font-bold mt-1">{{ $ditolak }}</h2>
                        </div>
                        <div class="bg-red-600 text-white p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Jumlah Peserta Magang --}}
                    <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Peserta Magang</p>
                            <h2 class="text-2xl font-bold mt-1">{{ $pesertaAktif }}</h2>
                        </div>
                        <div class="bg-green-600 text-white p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Jumlah Laporan Akhir --}}
                    <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Laporan Akhir</p>
                            <h2 class="text-2xl font-bold mt-1">{{ $laporanAkhir }}</h2>
                        </div>
                        <div class="bg-purple-600 text-white p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Jumlah Sertifikat --}}
                    <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Sertifikat yang telah diterbitkan</p>
                            <h2 class="text-2xl font-bold mt-1">{{ $sertifikat }}</h2>
                        </div>
                        <div class="bg-yellow-600 text-white p-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                    </div>
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
                    @forelse($aktivitasHariIni as $index => $peserta)
                    <tr>
                        <td class="border px-4 py-2">{{ $aktivitasHariIni->firstItem() + $index }}</td>
                        <td class="border px-4 py-2">{{ $peserta->nama_lengkap }}</td>
                        <td class="border px-4 py-2">{{ $peserta->asal_sekolah }}</td>
                        <td class="border px-4 py-2">
                            @if($peserta->absensi->isNotEmpty())
                                @php
                                    $absensi = $peserta->absensi->first();
                                    $hadir = ($absensi->waktu_pagi || $absensi->waktu_siang || $absensi->waktu_sore);
                                @endphp
                                @if($hadir)
                                    <span class="text-green-600 font-semibold">Hadir</span>
                                @else
                                    <span class="text-red-500 font-semibold">Tidak Hadir</span>
                                @endif
                            @else
                                <span class="text-gray-500 font-semibold">Belum Absen</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            @if($peserta->logbook->isNotEmpty())
                                <span class="text-green-600 font-semibold">Masuk</span>
                            @else
                                <span class="text-red-500 font-semibold">Belum</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="border px-4 py-2 text-center text-gray-500">Tidak ada aktivitas hari ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination Links --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $aktivitasHariIni->links() }}
        </div>
    </div>
</div>
@endsection