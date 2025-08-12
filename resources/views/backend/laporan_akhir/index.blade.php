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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporans as $laporan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $laporan->user->biodata->nama_lengkap ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($laporan->tanggal_upload)->translatedFormat('d-m-Y') }}</td>
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
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $laporan->id }}">
                            <i class="bi bi-eye"></i>
                        </button>
                        {{-- <a href="{{ route('admin.laporan.edit', $laporan->id) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-pencil-square"></i>
                        </a> --}}
                        <form action="{{ route('admin.laporan.destroy', $laporan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- Modal Detail --}}
                <div class="modal fade" id="detailModal{{ $laporan->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $laporan->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">DETAIL LAPORAN AKHIR PESERTA MAGANG</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Nama Lengkap:</strong> {{ $laporan->user->biodata->nama_lengkap ?? '-' }}</p>
                                <p><strong>Tanggal Upload Laporan:</strong> {{ \Carbon\Carbon::parse($laporan->tanggal_upload)->translatedFormat('d-m-Y') }}</p>
                                <p><strong>Judul Laporan:</strong> {{ $laporan->judul_laporan }}</p>
                                <p><strong>Pembimbing Industri:</strong> {{ $laporan->pembimbing_industri ?? '-' }}</p>
                                <p><strong>Unduh Laporan Magang:</strong></p>
                                @if ($laporan->file_laporan)
                                    <a href="{{ asset('storage/' . $laporan->file_laporan) }}" class="btn btn-outline-warning" target="_blank">
                                        <i class="bi bi-download"></i> Unduh Laporan
                                    </a>
                                @else
                                    <span class="text-muted">Belum ada file laporan</span>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data laporan akhir.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
