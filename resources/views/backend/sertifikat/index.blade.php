@extends('layouts.backend')

@section('content')
<style>
    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }
        h4 {
            font-size: 1.2rem;
            margin-bottom: 1rem !important;
        }
        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
        .modal-dialog {
            margin: 0.5rem;
        }
        .form-label {
            font-size: 0.9rem;
        }
        .form-control, .form-control-plaintext {
            font-size: 0.9rem;
        }
        .form-text {
            font-size: 0.8rem;
        }
    }
    @media (max-width: 576px) {
        .table {
            font-size: 0.8rem;
        }
        .btn-sm {
            padding: 0.15rem 0.3rem;
            font-size: 0.7rem;
        }
        .modal-title {
            font-size: 1.1rem;
        }
    }
</style>

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
                        @if ($user->sertifikat && $user->sertifikat->file_sertifikat)
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Sudah Diunggah</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Belum Diunggah</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->sertifikat && $user->sertifikat->file_sertifikat)
                            <a href="{{ asset('storage/' . $user->sertifikat->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-warning">
                                <i class="bi bi-download"></i> Unduh Sertifikat
                            </a>
                        @elseif ($user->laporanAkhir)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $user->id }}">
                                <i class="bi bi-upload"></i> Upload Sertifikat
                            </button>
                        @else
                            <button class="btn btn-sm btn-secondary" disabled title="Peserta belum mengupload laporan akhir">
                                <i class="bi bi-upload"></i> Upload Sertifikat
                            </button>
                        @endif
                    </td>
                </tr>

                <!-- Modal Upload Sertifikat -->
                <div class="modal fade" id="uploadModal{{ $user->id }}" tabindex="-1" aria-labelledby="uploadModalLabel{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadModalLabel{{ $user->id }}">Upload Sertifikat - {{ $user->biodata->nama_lengkap }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.sertifikat.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    
                                    <div class="mb-3">
                                        <label for="tanggal_mulai_display{{ $user->id }}" class="form-label">Tanggal Mulai Magang</label>
                                        <input type="text" class="form-control-plaintext" id="tanggal_mulai_display{{ $user->id }}" 
                                               value="{{ \Carbon\Carbon::parse($user->biodata->tanggal_mulai)->translatedFormat('d F Y') }}" readonly>
                                        <input type="hidden" name="tanggal_mulai" value="{{ $user->biodata->tanggal_mulai }}">
                                        <div class="form-text text-muted">Otomatis dari data biodata peserta</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="tanggal_selesai_display{{ $user->id }}" class="form-label">Tanggal Selesai Magang</label>
                                        <input type="text" class="form-control-plaintext" id="tanggal_selesai_display{{ $user->id }}" 
                                               value="{{ \Carbon\Carbon::parse($user->biodata->tanggal_selesai)->translatedFormat('d F Y') }}" readonly>
                                        <input type="hidden" name="tanggal_selesai" value="{{ $user->biodata->tanggal_selesai }}">
                                        <div class="form-text text-muted">Otomatis dari data biodata peserta</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="file_sertifikat{{ $user->id }}" class="form-label">File Sertifikat (PDF)</label>
                                        <input type="file" class="form-control" id="file_sertifikat{{ $user->id }}" name="file_sertifikat" 
                                               accept=".pdf" required>
                                        <div class="form-text">Maksimal 5MB, format PDF</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Upload Sertifikat</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data peserta magang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
