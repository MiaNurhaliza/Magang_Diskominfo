@extends('layouts.backend')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Data Absensi Peserta Magang</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Hari, Tanggal</th>
                    <th>Absen Pagi</th>
                    <th>Absen Siang</th>
                    <th>Absen Sore</th>
                    <th>Keterangan</th>
                    <th>Surat Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absensis as $absen)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $absen->biodata->nama_lengkap ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d-m-Y') }}</td>
                    <td>
                        @if($absen->izin)
                            <span class="badge bg-warning">{{ $absen->izin->jenis }}</span>
                        @else
                            @if($absen->pagi)
                                {{ $absen->pagi }}
                                @if($absen->waktu_pagi)
                                    <br><small class="text-muted">({{ \Carbon\Carbon::parse($absen->waktu_pagi)->format('H:i:s') }} WIB)</small>
                                @endif
                            @else
                                -
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($absen->izin)
                            <span class="badge bg-warning">{{ $absen->izin->jenis }}</span>
                        @else
                            @if($absen->siang)
                                {{ $absen->siang }}
                                @if($absen->waktu_siang)
                                    <br><small class="text-muted">({{ \Carbon\Carbon::parse($absen->waktu_siang)->format('H:i:s') }} WIB)</small>
                                @endif
                            @else
                                -
                            @endif
                        @endif
                    </td>
                    <td>
                        @if($absen->izin)
                            <span class="badge bg-warning">{{ $absen->izin->jenis }}</span>
                        @else
                            @if($absen->sore)
                                {{ $absen->sore }}
                                @if($absen->waktu_sore)
                                    <br><small class="text-muted">({{ \Carbon\Carbon::parse($absen->waktu_sore)->format('H:i:s') }} WIB)</small>
                                @endif
                            @else
                                -
                            @endif
                        @endif
                    </td>
                    <td>{{ $absen->keterangan ?? $absen->izin->keterangan ?? '-' }}</td>
                    <td>
                        @if ($absen->file_keterangan || ($absen->izin && $absen->izin->bukti_file))
                            <a href="{{ asset('storage/' . ($absen->file_keterangan ?? $absen->izin->bukti_file)) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-text"></i> Lihat Surat
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.absensi.destroy', $absen->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if($absensis->isEmpty())
                <tr>
                    <td colspan="9" class="text-center">Belum ada data absensi.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $absensis->links() }}
    </div>
</div>
@endsection
