@extends('layouts.pembimbing')

@section('title', 'Dashboard Pembimbing')

@section('content')
<div class="container px-6 py-8 mx-auto">
    <h3 class="text-3xl font-medium text-gray-700">Dashboard Pembimbing</h3>
    
    <div class="mt-6">
        <div class="flex flex-wrap -mx-6">
            <div class="w-full px-6 sm:w-1/2 xl:w-1/4">
                <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
                    <div class="p-3 bg-indigo-600 bg-opacity-75 rounded-full">
                        <i class="bi bi-people text-white text-2xl"></i>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $totalMahasiswa }}</h4>
                        <div class="text-gray-500">Mahasiswa Bimbingan</div>
                    </div>
                </div>
            </div>

            <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/4 sm:mt-0">
                <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
                    <div class="p-3 bg-green-600 bg-opacity-75 rounded-full">
                        <i class="bi bi-calendar-check text-white text-2xl"></i>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $totalAbsensi }}</h4>
                        <div class="text-gray-500">Total Absensi</div>
                    </div>
                </div>
            </div>

            <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/4 xl:mt-0">
                <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
                    <div class="p-3 bg-yellow-600 bg-opacity-75 rounded-full">
                        <i class="bi bi-journal-text text-white text-2xl"></i>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $totalLogbook }}</h4>
                        <div class="text-gray-500">Total Logbook</div>
                    </div>
                </div>
            </div>

            <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/4 xl:mt-0">
                <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
                    <div class="p-3 bg-purple-600 bg-opacity-75 rounded-full">
                        <i class="bi bi-file-earmark-check text-white text-2xl"></i>
                    </div>
                    <div class="mx-5">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $laporanSelesai }}</h4>
                        <div class="text-gray-500">Laporan Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mahasiswa Bimbingan Table -->
    <div class="mt-8">
        <div class="flex flex-col mt-8">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <div class="px-6 py-4 bg-white border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Mahasiswa Bimbingan</h3>
                    </div>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Asal Sekolah</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($mahasiswas as $index => $mahasiswa)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $mahasiswa->nama_lengkap }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-900">{{ $mahasiswa->nis_nim }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-900">{{ $mahasiswa->asal_sekolah }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    {{ \Carbon\Carbon::parse($mahasiswa->tanggal_mulai)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($mahasiswa->tanggal_selesai)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    @if($mahasiswa->status == 'Diterima')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $mahasiswa->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                    <a href="{{ route('pembimbing.absensi') }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Absensi</a>
                                    <a href="{{ route('pembimbing.logbook') }}" class="text-green-600 hover:text-green-900 mr-3">Logbook</a>
                                    <a href="{{ route('pembimbing.laporan-akhir') }}" class="text-blue-600 hover:text-blue-900">Laporan</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 border-b border-gray-200">
                                    <div class="flex flex-col items-center">
                                        <i class="bi bi-people text-4xl text-gray-300 mb-2"></i>
                                        <p>Belum ada mahasiswa yang dibimbing</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection