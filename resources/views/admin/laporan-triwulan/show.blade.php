@extends('layouts.app')

@section('title', 'Detail Laporan Triwulanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Detail Laporan {{ $laporanTriwulan->periode }}
                        </h3>
                        <div class="btn-group">
                            @if($laporanTriwulan->status === 'draft')
                                <a href="{{ route('admin.laporan-triwulan.edit', $laporanTriwulan->id) }}" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>
                                    Edit
                                </a>
                            @endif
                            @if($laporanTriwulan->file_pdf)
                                <a href="{{ route('admin.laporan-triwulan.download', $laporanTriwulan->id) }}" 
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-download me-1"></i>
                                    Download PDF
                                </a>
                            @endif
                            <a href="{{ route('admin.laporan-triwulan.index') }}" 
                               class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Umum -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5 class="info-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informasi Umum
                                </h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%"><strong>Periode</strong></td>
                                        <td>: {{ $laporanTriwulan->periode }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tahun</strong></td>
                                        <td>: {{ $laporanTriwulan->tahun }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Triwulan</strong></td>
                                        <td>: {{ $laporanTriwulan->quarter }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Periode</strong></td>
                                        <td>: {{ $laporanTriwulan->tanggal_mulai->format('d M Y') }} - {{ $laporanTriwulan->tanggal_selesai->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>: 
                                            @if($laporanTriwulan->status === 'draft')
                                                <span class="badge bg-warning">Draft</span>
                                            @elseif($laporanTriwulan->status === 'published')
                                                <span class="badge bg-success">Published</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dibuat Oleh</strong></td>
                                        <td>: {{ $laporanTriwulan->creator->name ?? 'System' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Dibuat</strong></td>
                                        <td>: {{ $laporanTriwulan->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <h5 class="info-title">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Statistik Mahasiswa
                                </h5>
                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <div class="stat-number">{{ $laporanTriwulan->total_mahasiswa }}</div>
                                        <div class="stat-label">Total Mahasiswa</div>
                                    </div>
                                    @if($laporanTriwulan->data_mahasiswa)
                                        @php
                                            $evaluations = collect($laporanTriwulan->data_mahasiswa)->pluck('evaluasi')->countBy();
                                        @endphp
                                        <div class="stat-item">
                                            <div class="stat-number text-success">{{ $evaluations->get('Sangat Baik', 0) + $evaluations->get('Baik', 0) }}</div>
                                            <div class="stat-label">Evaluasi Baik+</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-number text-warning">{{ $evaluations->get('Cukup', 0) }}</div>
                                            <div class="stat-label">Evaluasi Cukup</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-number text-danger">{{ $evaluations->get('Kurang', 0) }}</div>
                                            <div class="stat-label">Evaluasi Kurang</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Eksekutif -->
                    @if($laporanTriwulan->ringkasan)
                    <div class="mb-4">
                        <div class="info-box">
                            <h5 class="info-title">
                                <i class="fas fa-file-alt me-2"></i>
                                Ringkasan Eksekutif
                            </h5>
                            <div class="ringkasan-content">
                                {!! nl2br(e($laporanTriwulan->ringkasan)) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Data Mahasiswa -->
                    @if($laporanTriwulan->data_mahasiswa && count($laporanTriwulan->data_mahasiswa) > 0)
                    <div class="mb-4">
                        <div class="info-box">
                            <h5 class="info-title">
                                <i class="fas fa-users me-2"></i>
                                Data Mahasiswa ({{ count($laporanTriwulan->data_mahasiswa) }} orang)
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Asal Sekolah</th>
                                            <th>Jurusan</th>
                                            <th>Kehadiran</th>
                                            <th>Logbook</th>
                                            <th>Evaluasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($laporanTriwulan->data_mahasiswa as $index => $mahasiswa)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $mahasiswa['nama'] }}</strong><br>
                                                <small class="text-muted">{{ $mahasiswa['nis_nim'] }}</small>
                                            </td>
                                            <td>{{ $mahasiswa['asal_sekolah'] }}</td>
                                            <td>{{ $mahasiswa['jurusan'] }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" 
                                                         role="progressbar" 
                                                         style="width: {{ $mahasiswa['persentase_kehadiran'] }}%"
                                                         aria-valuenow="{{ $mahasiswa['persentase_kehadiran'] }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        {{ number_format($mahasiswa['persentase_kehadiran'], 1) }}%
                                                    </div>
                                                </div>
                                                {{-- <small class="text-muted">
                                                    {{ $mahasiswa['total_hadir'] }}/{{ $mahasiswa['total_hari_kerja'] }} hari
                                                </small> --}}
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $mahasiswa['total_logbook'] }} entries</span>
                                            </td>
                                            <td>
                                                @php
                                                    $evaluasiClass = match($mahasiswa['evaluasi']) {
                                                        'Sangat Baik' => 'bg-success',
                                                        'Baik' => 'bg-primary',
                                                        'Cukup' => 'bg-warning',
                                                        'Kurang' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $evaluasiClass }}">{{ $mahasiswa['evaluasi'] }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- File PDF Info -->
                    @if($laporanTriwulan->file_pdf)
                    <div class="mb-4">
                        <div class="info-box">
                            <h5 class="info-title">
                                <i class="fas fa-file-pdf me-2"></i>
                                File PDF
                            </h5>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-pdf text-danger me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <strong>{{ basename($laporanTriwulan->file_pdf) }}</strong><br>
                                    <small class="text-muted">
                                        Dibuat: {{ $laporanTriwulan->updated_at->format('d M Y H:i') }}
                                    </small>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('admin.laporan-triwulan.download', $laporanTriwulan->id) }}" 
                                       class="btn btn-success">
                                        <i class="fas fa-download me-1"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-header {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.card-header .card-title {
    color: white;
}

.info-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #17a2b8;
}

.info-title {
    color: #495057;
    margin-bottom: 1rem;
    font-weight: 600;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #17a2b8;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.5rem;
}

.ringkasan-content {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    line-height: 1.6;
}

.table th {
    font-weight: 600;
    border-top: none;
}

.progress {
    background-color: #e9ecef;
}

.btn-group .btn {
    margin-left: 0.25rem;
}

.btn:hover {
    transform: translateY(-1px);
    transition: transform 0.2s;
}
</style>
@endpush