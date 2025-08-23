@extends('layouts.backend')

@section('title', 'Buat Laporan Triwulanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Buat Laporan Triwulanan Baru
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.laporan-triwulan.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tahun" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Tahun <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('tahun') is-invalid @enderror" 
                                            id="tahun" 
                                            name="tahun" 
                                            required>
                                        <option value="">Pilih Tahun</option>
                                        @for($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}" 
                                                    {{ old('tahun', $currentYear) == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                            required>
                                        <option value="">Pilih Triwulan</option>
                                        <option value="1" {{ old('quarter', $currentQuarter) == 1 ? 'selected' : '' }}>
                                            Triwulan I (Januari - Maret)
                                        </option>
                                        <option value="2" {{ old('quarter', $currentQuarter) == 2 ? 'selected' : '' }}>
                                            Triwulan II (April - Juni)
                                        </option>
                                        <option value="3" {{ old('quarter', $currentQuarter) == 3 ? 'selected' : '' }}>
                                            Triwulan III (Juli - September)
                                        </option>
                                        <option value="4" {{ old('quarter', $currentQuarter) == 4 ? 'selected' : '' }}>
                                            Triwulan IV (Oktober - Desember)
                                        </option>
                                    </select>
                                    @error('quarter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                      rows="5" 
                                      placeholder="Masukkan ringkasan atau catatan khusus untuk laporan triwulan ini...">{{ old('ringkasan') }}</textarea>
                            @error('ringkasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Ringkasan ini akan ditampilkan di laporan PDF sebagai catatan eksekutif.
                            </div>
                        </div>

                        <!-- Preview Info -->
                        <div class="alert alert-info" id="previewInfo" style="display: none;">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Laporan
                            </h6>
                            <div id="previewContent"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.laporan-triwulan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                
                                <i class="fas fa-save me-1"></i>
                                Buat Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tahunSelect = document.getElementById('tahun');
    const quarterSelect = document.getElementById('quarter');
    const previewInfo = document.getElementById('previewInfo');
    const previewContent = document.getElementById('previewContent');

    function updatePreview() {
        const tahun = tahunSelect.value;
        const quarter = quarterSelect.value;
        
        if (tahun && quarter) {
            const quarterNames = {
                '1': 'Triwulan I (Januari - Maret)',
                '2': 'Triwulan II (April - Juni)',
                '3': 'Triwulan III (Juli - September)',
                '4': 'Triwulan IV (Oktober - Desember)'
            };
            
            const quarterDates = {
                '1': { start: '01 Januari', end: '31 Maret' },
                '2': { start: '01 April', end: '30 Juni' },
                '3': { start: '01 Juli', end: '30 September' },
                '4': { start: '01 Oktober', end: '31 Desember' }
            };
            
            previewContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Periode:</strong> Q${quarter} ${tahun}<br>
                        <strong>Nama:</strong> ${quarterNames[quarter]} <br>
                        <strong>Tanggal:</strong> ${quarterDates[quarter].start} - ${quarterDates[quarter].end} ${tahun}<br>
                    </div>
                    
                
            `;
            previewInfo.style.display = 'block';
        } else {
            previewInfo.style.display = 'none';
        }
    }

    tahunSelect.addEventListener('change', updatePreview);
    quarterSelect.addEventListener('change', updatePreview);
    
    // Initial preview update
    updatePreview();
});
</script>
@endpush

@push('styles')
<style>
.card-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.card-header .card-title {
    color: white;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-select:focus,
.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.alert-info {
    border-left: 4px solid #17a2b8;
}

.text-danger {
    font-weight: bold;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
}
</style>
@endpush