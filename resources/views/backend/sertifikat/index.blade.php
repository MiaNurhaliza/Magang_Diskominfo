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
                    <th>Status Sertifikat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sertifikats as $sertifikat)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sertifikat->user->biodata->nama_lengkap ?? '-' }}</td>
                    <td>{{ $sertifikat->user->biodata->asal_sekolah ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($sertifikat->tanggal_selesai)->translatedFormat('d-m-Y') }}</td>
                    <td>
                        @if ($sertifikat->file)
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Sudah Diunggah</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Belum Diunggah</span>
                        @endif
                    </td>
                    <td>
                        @if ($sertifikat->file)
                            <a href="{{ asset('storage/' . $sertifikat->file) }}" target="_blank" class="btn btn-sm btn-warning">
                                <i class="bi bi-download"></i> Unduh Sertifikat
                            </a>
                        @else
                            <a href="{{ route('admin.sertifikat.edit', $sertifikat->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-upload"></i> Upload Sertifikat
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data sertifikat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
