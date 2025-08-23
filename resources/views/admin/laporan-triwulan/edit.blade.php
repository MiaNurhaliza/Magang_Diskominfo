@extends('layouts.app')

@section('title', 'Edit Laporan Triwulanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Laporan {{ $laporantriwulan>periode }}
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.laporan-triwulan.update', $laporantriwulan>id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Info Alert -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Laporan
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Periode:</strong> {{ $laporantriwulan>periode }}<br>
                                    <strong>Tanggal:</strong> {{ $laporantriwulan>tanggal_mulai->format('d M Y') }} - {{ $laporantriwulan>tanggal_selesai->format('d M Y') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Total Mahasiswa:</strong> {{ $laporantriwulan>total_mahasiswa }} orang<br>
                                    <strong>Status:</strong> 
                                    @if($laporantriwulan>status === 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @else
                                        <span class="badge bg-success">Published</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tahun" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Tahun <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('tahun') is-invalid @enderror" 
                                           id="tahun" 
                                           name="tahun" 
                                           value="{{ old('tahun', $laporantriwulan>tahun) }}"
                                           readonly
                                           style="background-color: #f8f9fa;">
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-lock me-1"></i>
                                        Tahun tidak dapat diubah setelah laporan dibuat.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quarter" class="form-label">
                                        <i class="fas fa-calendar-week me-1"></i>
                                        Triwulan <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('quarter') is-invalid @enderror" 
                                            id="quarter" 
                                            name="quarter" 
                                            disabled
                                            style="background-color: #f8f9fa;">
                                        <option value="1" {{ $laporantriwulan>quarter == 1 ? 'selected' : '' }}>
                                            Triwulan I (Januari - Maret)
                                        </option>
                                        <option value="2" {{ $laporantriwulan>quarter == 2 ? 'selected' : '' }}>
                                            Triwulan II (April - Juni)
                                        </option>
                                        <option value="3" {{ $laporantriwulan>quarter == 3 ? 'selected' : '' }}>
                                            Triwulan III (Juli - September)
                                        </option>
                                        <option value="4" {{ $laporantriwulan>quarter == 4 ? 'selected' : '' }}>
                                            Triwulan IV (Oktober - Desember)
                                        </option>
                                    </select>
                                    <input type="hidden" name="quarter" value="{{ $laporantriwulan>quarter }}">
                                    @error('quarter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-lock me-1"></i>
                                        Triwulan tidak dapat diubah setelah laporan dibuat.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ringkasan" class="form-label">
                                <i class="fas fa-file-alt me-1"></i>
                                Ringkasan Eksekutif
                            </label>
                            <textarea class="form-control @error('ringkasan') is-invalid @enderror" 
                                      id="ringkasan" 
                                      name="ringkasan" 
                                      rows="6" 
                                      placeholder="Masukkan ringkasan atau catatan khusus untuk laporan triwulan ini...">{{ old('ringkasan', $laporantriwulan>ringkasan) }}</textarea>
                            @error('ringkasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Ringkasan ini akan ditampilkan di laporan PDF sebagai catatan eksekutif.
                            </div>
                        </div>

                        <!-- Data Mahasiswa Preview -->
                        @if($laporantriwulan>data_mahasiswa && count($laporantriwulan>data_mahasiswa) > 0)
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-users me-1"></i>
                                Data Mahasiswa ({{ count($laporantriwulan>data_mahasiswa) }} orang)
                            </label>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Universitas</th>
                                            <th>Kehadiran</th>
                                            <th>Logbook</th>
                                            <th>Evaluasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(array_slice($laporantriwulan>data_mahasiswa, 0, 5) as $index => $mahasiswa)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $mahasiswa['nama'] }}</strong><br>
                                                <small class="text-muted">{{ $mahasiswa['nim'] }}</small>
                                            </td>
                                            <td>{{ $mahasiswa['universitas'] }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ number_format($mahasiswa['persentase_kehadiran'], 1) }}%</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $mahasiswa['total_logbook'] }}</span>
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
                                        @if(count($laporantriwulan>data_mahasiswa) > 5)
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="fas fa-ellipsis-h me-1"></i>
                                                dan {{ count($laporantriwulan>data_mahasiswa) - 5 }} mahasiswa lainnya
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Data mahasiswa akan diperbarui otomatis saat laporan disimpan.
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.laporan-triwulan.show', $laporantriwulan>id) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Kembali
                                </a>
                            </div>
                            <div>
                                @if($laporantriwulan>status === 'draft')
                                    <button type="submit" name="action" value="save_draft" class="btn btn-warning me-2">
                                        <i class="fas fa-save me-1"></i>
                                        Simpan Draft
                                    </button>
                                    <button type="submit" name="action" value="publish" class="btn btn-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Publish Laporan
                                    </button>
                                @else
                                    <button type="submit" name="action" value="update" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Update Laporan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Regenerate Data Modal -->
<div class="modal fade" id="regenerateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-sync-alt me-2"></i>
                    Regenerate Data Mahasiswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin meregenerasi data mahasiswa untuk laporan ini?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Tindakan ini akan memperbarui semua data mahasiswa, statistik kehadiran, dan evaluasi berdasarkan data terbaru.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <form action="{{ route('admin.laporan-triwulan.regenerate', $laporantriwulan>id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync-alt me-1"></i>
                        Regenerate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea
    const textarea = document.getElementById('ringkasan');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Initial resize
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }

    // Confirm before publish
    const publishBtn = document.querySelector('button[value="publish"]');
    if (publishBtn) {
        publishBtn.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin mempublish laporan ini? Setelah dipublish, laporan tidak dapat diedit lagi.')) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.card-header {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: #212529;
}

.card-header .card-title {
    color: #212529;
    font-weight: 600;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control:focus,
.form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.alert-info {
    border-left: 4px solid #17a2b8;
}

.text-danger {
    font-weight: bold;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    border: none;
    color: #212529;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
    transform: translateY(-1px);
    color: #212529;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    transform: translateY(-1px);
}

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.table th {
    font-weight: 600;
    border-top: none;
}

.form-text {
    font-size: 0.875rem;
}

textarea {
    resize: vertical;
    min-height: 120px;
}
</style>
@endpush