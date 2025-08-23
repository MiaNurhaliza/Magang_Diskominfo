@extends('layouts.backend')

@section('title', 'Laporan Triwulanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Laporan Triwulanan Magang
                    </h3>
                    <a href="{{ route('admin.laporan-triwulan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Buat Laporan Baru
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($laporans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Periode</th>
                                        <th width="12%">Tahun</th>
                                        <th width="12%">Triwulan</th>
                                        <th width="15%">Total Mahasiswa</th>
                                        {{-- <th width="12%">Status</th> --}}
                                        <th width="15%">Dibuat</th>
                                        <th width="14%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporans as $index => $laporan)
                                    <tr>
                                        <td>{{ $laporans->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $laporan->periode }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $laporan->tanggal_mulai->format('d/m/Y') }} - 
                                                {{ $laporan->tanggal_selesai->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span>{{ $laporan->tahun }}</span>
                                        </td>
                                        <td>
                                            <span>Q{{ $laporan->quarter }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $laporan->total_mahasiswa }} orang</span>
                                        </td>
                                        {{-- <td class="text-center">
                                            @if($laporan->status == 'final')
                                                <span>
                                                    <i class="fas fa-check me-1"></i>Final
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-edit me-1"></i>Draft
                                                </span>
                                            @endif
                                        </td> --}}
                                        <td>
                                            <small class="text-muted">
                                                {{ $laporan->created_at->format('d/m/Y H:i') }}
                                                <br>
                                                oleh {{ $laporan->creator->name ?? 'Admin' }}
                                            </small>
                                        </td>
                                        <td>
                                            {{-- <div class="btn-group" role="group">
                                                <a href="{{ route('admin.laporan-triwulan.show', $laporan) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a> --}}
                                                
                                                <a href="{{ route('admin.laporan-triwulan.download', $laporan) }}" 
                                                   class="btn btn-sm btn-success me-1" 
                                                   title="Download PDF">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="Hapus"
                                                        onclick="confirmDelete({{ $laporan->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $laporans->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Laporan Triwulanan</h5>
                            <p class="text-muted">Mulai buat laporan triwulanan pertama Anda.</p>
                            <a href="{{ route('admin.laporan-triwulan.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Buat Laporan Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus laporan triwulanan ini?</p>
                <p class="text-danger"><strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan dan akan menghapus file PDF yang terkait.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Batal
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(laporanId) {
    console.log('Delete function called for ID:', laporanId);
    
    const deleteForm = document.getElementById('deleteForm');
    if (!deleteForm) {
        console.error('Delete form not found');
        return;
    }
    
    deleteForm.action = `{{ url('admin/laporan-triwulan') }}/${laporanId}`;
    console.log('Form action set to:', deleteForm.action);
    
    const deleteModalElement = document.getElementById('deleteModal');
    if (!deleteModalElement) {
        console.error('Delete modal not found');
        return;
    }
    
    try {
        const deleteModal = new bootstrap.Modal(deleteModalElement);
        deleteModal.show();
        console.log('Modal shown successfully');
    } catch (error) {
        console.error('Error showing modal:', error);
    }
}

// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing scripts');
    
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Test if Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded!');
    } else {
        console.log('Bootstrap loaded successfully');
    }
});
</script>
@endpush

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-header .card-title {
    color: white;
}

.badge {
    font-size: 0.75em;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.075);
}
</style>
@endpush