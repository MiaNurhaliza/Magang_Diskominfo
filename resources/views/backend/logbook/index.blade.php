@extends('layouts.backend')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Data Logbook Peserta Magang</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Hari, Tanggal</th>
                    <th>Kegiatan</th>
                    <th>Langkah Kerja</th>
                    <th>Hasil Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logbooks as $logbook)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $logbook->user->biodata->nama_lengkap ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d-m-Y') }}</td>
                    <td>{{ $logbook->kegiatan }}</td>
                    <td>{{ $logbook->langkah_kerja ?? '-' }}</td>
                    <td>
                        @if ($logbook->hasil_akhir)
                            <a href="{{ asset('storage/' . $logbook->hasil_akhir) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-image"></i> Lihat Gambar
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.logbook.destroy', $logbook->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data logbook.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $logbooks->links() }}
    </div>
</div>
@endsection
