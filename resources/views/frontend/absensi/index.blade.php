@extends('layouts.peserta')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-2xl font-bold mb-4">Selamat Datang, {{ Auth::user()->name }}</h1>

    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-lg font-semibold">Absensi</h2>
                <p class="text-sm text-red-500">Absensi diambil di area Diskominfo Kota Bukittinggi</p>
            </div>
            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                + Ajukan Izin
            </a>
        </div>

        {{-- Tombol ambil absen --}}
        @if($bolehAbsen && !$sudahAbsen)
            <form action="{{ route('user.absensi.store') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="tanggal" value="{{ now()->toDateString() }}">
                <input type="hidden" name="pagi" value="hadir">
                <input type="hidden" name="siang" value="-">
                <input type="hidden" name="sore" value="-">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    Ambil Absen Pagi
                </button>
            </form>
        @elseif(!$bolehAbsen)
            <div class="mb-4 text-yellow-600 font-medium">
                Belum memasuki jadwal magang atau sudah selesai.
            </div>
        @elseif($sudahAbsen)
            <div class="mb-4 text-green-600 font-medium">
                Anda sudah absen hari ini.
            </div>
        @endif

        {{-- Tabel absensi --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Absen Pagi</th>
                        <th class="px-4 py-2 border">Absen Siang</th>
                        <th class="px-4 py-2 border">Absen Sore</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $index => $item)
                        <tr>
                            <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                @if($item->pagi == 'hadir')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-600 text-sm">Hadir</span>
                                @elseif($item->pagi == 'izin')
                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-600 text-sm">Izin</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm">Alfa</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border text-center">
                                @if($item->siang == 'hadir')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-600 text-sm">Hadir</span>
                                @elseif($item->siang == 'izin')
                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-600 text-sm">Izin</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm">Alfa</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border text-center">
                                @if($item->sore == 'hadir')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-600 text-sm">Hadir</span>
                                @elseif($item->sore == 'izin')
                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-600 text-sm">Izin</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm">Alfa</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data absensi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
