@extends('layouts.pembimbing')

@section('title', 'Absensi Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Absensi Mahasiswa</h4>
                <div class="page-title-right">
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Absensi Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">Data Absensi</h4>
                </div>
                <div class="card-body">
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
                                        <form action="{{ route('pembimbing.absensi.destroy', $absen->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus data ini?')">
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

                    {{-- Pagination removed since we're using Collection instead of Paginator --}}
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($absensis as $absen)
{{-- <!-- Detail Modal -->
< class="modal fade" id="detailModal{{ $absen->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4"><strong>Mahasiswa:</strong></div>
                    <div class="col-sm-8">{{ $absen->biodata->nama_lengkap ?? '-' }}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-4"><strong>NIM:</strong></div>
                    <div class="col-sm-8">{{ $absen->biodata->nis_nim ?? '-' }}</div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-4"><strong>Tanggal:</strong></div>
                    <div class="col-sm-8">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y') }}</div>
                </div>
                @if($absen->pagi)
                <div class="row mt-2">
                    <div class="col-sm-4"><strong>Absen Pagi:</strong></div>
                    <div class="col-sm-8">
                        {{ $absen->pagi }}
                        @if($absen->waktu_pagi)
                            <br><small class="text-muted">({{ \Carbon\Carbon::parse($absen->waktu_pagi)->format('H:i:s') }} WIB)</small>
                        @endif
                    </div>
                </div>
                @endif
                @if($absen->siang)
                <div class="row mt-2">
                    <div class="col-sm-4"><strong>Absen Siang:</strong></div>
                    <div class="col-sm-8">
                        {{ $absen->siang }}
                        @if($absen->waktu_siang)
                            <br><small class="text-muted">({{ \Carbon\Carbon::parse($absen->waktu_siang)->format('H:i:s') }} WIB)</small>
                        @endif
                    </div>
                </div>
                @endif
                @if($absen->sore)
                <div class="row mt-2">
                    <div class="col-sm-4"><strong>Absen Sore:</strong></div>
                    <div class="col-sm-8">
                        {{ $absen->sore }}
                        @if($absen->waktu_sore)
                            <br><small class="text-muted">({{ \Carbon\Carbon::parse($absen->waktu_sore)->format('H:i:s') }} WIB)</small>
                        @endif
                    </div>
                </div>
                @endif
                @if($absen->keterangan || ($absen->izin && $absen->izin->keterangan))
                <div class="row mt-2">
                    <div class="col-sm-4"><strong>Keterangan:</strong></div>
                    <div class="col-sm-8">{{ $absen->keterangan ?? $absen->izin->keterangan }}</div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div> --}}

@endforeach
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Auto submit form when filter changes
        $('#mahasiswa, #bulan, #tahun').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>
@endpush