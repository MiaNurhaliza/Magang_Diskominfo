@extends('layouts.backend')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Data Laporan Akhir Peserta Magang</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Tanggal Upload</th>
                    <th>Judul Laporan</th>
                    <th>Laporan</th>
                    <th>Nilai Magang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporans as $laporan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $laporan->user->biodata->nama_lengkap ?? '-' }}</td>
                    <td>{{ $laporan->created_at->translatedFormat('d-m-Y') }}</td>
                    <td>{{ $laporan->judul_laporan ?? '-' }}</td>
                    <td>
                        @if($laporan->file_laporan)
                        <a href="{{ asset('storage/' . $laporan->file_laporan) }}" target="_blank" class="btn btn-sm btn-outline-purple">
                            <i class="bi bi-file-earmark-text"></i> Lihat Laporan
                        </a>
                        @else
                        <span class="text-muted">Tidak ada file</span>
                        @endif
                    </td>
                    <td>
                        @if($laporan->file_nilai_magang)
                        <a href="{{ asset('storage/' . $laporan->file_nilai_magang) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-file-earmark-text"></i> Lihat Nilai
                        </a>
                        @else
                        <span class="text-muted">Tidak ada file</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.laporan.destroy', $laporan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data laporan akhir.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
