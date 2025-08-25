@extends('layouts.backend')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Sertifikat Magang Peserta</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Asal Sekolah/Kampus</th>
                    <th>Tanggal Selesai</th>
                    <th>Status Laporan</th>
                    <th>Status Sertifikat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->biodata->nama_lengkap ?? '-' }}</td>
                    <td>{{ $user->biodata->asal_sekolah ?? '-' }}</td>
                    <td>{{ $user->biodata->tanggal_selesai ? \Carbon\Carbon::parse($user->biodata->tanggal_selesai)->translatedFormat('d-m-Y') : '-' }}</td>
                    <td>
                        @if ($user->laporanAkhir && $user->laporanAkhir->status === 'approved')
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Disetujui</span>
                        @elseif ($user->laporanAkhir && $user->laporanAkhir->status === 'revision')
                            <span class="badge bg-warning"><i class="bi bi-arrow-clockwise"></i> Perlu Revisi</span>
                        @elseif ($user->laporanAkhir && $user->laporanAkhir->status === 'pending')
                            <span class="badge bg-info"><i class="bi bi-clock"></i> Menunggu Review</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Belum Upload</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->sertifikat && $user->sertifikat->file_sertifikat)
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Sudah Dibuat</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Belum Dibuat</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->sertifikat && $user->sertifikat->file_sertifikat)
                            <a href="{{ asset('storage/' . $user->sertifikat->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-warning">
                                <i class="bi bi-download"></i> Unduh Sertifikat
                            </a>
                        @elseif ($user->laporanAkhir && $user->laporanAkhir->status === 'approved')
                            <form action="{{ route('admin.sertifikat.generate', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Generate sertifikat untuk {{ $user->biodata->nama_lengkap }}?')">
                                    <i class="bi bi-file-earmark-plus"></i> Generate Sertifikat
                                </button>
                            </form>
                        @else
                            <button class="btn btn-sm btn-secondary" disabled title="Laporan belum disetujui pembimbing">
                                <i class="bi bi-file-earmark-plus"></i> Generate Sertifikat
                            </button>
                        @endif
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data peserta magang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
@endsection
