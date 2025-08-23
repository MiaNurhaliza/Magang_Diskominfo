@extends('layouts.pembimbing')

@section('title', 'Laporan Akhir Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Laporan Akhir Mahasiswa</h4>
                <div class="page-title-right">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Laporan Akhir Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">Data Laporan Akhir</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tanggal Upload</th>
                                    <th>Judul Laporan</th>
                                    <th>Laporan</th>
                                    <th>Nilai Magang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($laporanAkhirs as $laporan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $laporan->biodata->nama_lengkap ?? '-' }}</td>
                                    <td>{{ $laporan->created_at->translatedFormat('d-m-Y') }}</td>
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
                                        @if($laporan->file_nilai_magang)
                                        <a href="{{ asset('storage/' . $laporan->file_nilai_magang) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-file-earmark-text"></i> Lihat Nilai
                                        </a>
                                        @else
                                        <span class="text-muted">Tidak ada file</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('pembimbing.laporan-akhir.destroy', $laporan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Detail Modal -->
                                <div class="modal fade" id="detailModal{{ $laporan->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Laporan Akhir</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Mahasiswa:</label>
                                                            <p>{{ $laporan->biodata->nama ?? 'Nama tidak tersedia' }} ({{ $laporan->biodata->nim ?? 'NIM tidak tersedia' }})</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Universitas:</label>
                                                            <p>{{ $laporan->biodata->universitas ?? 'Universitas tidak tersedia' }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Judul Laporan:</label>
                                                            <p>{{ $laporan->judul }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Status:</label>
                                                            <p>
                                                                @switch($laporan->status)
                                                                    @case('draft')
                                                                        <span class="badge bg-secondary">Draft</span>
                                                                        @break
                                                                    @case('submitted')
                                                                        <span class="badge bg-warning">Diserahkan</span>
                                                                        @break
                                                                    @case('reviewed')
                                                                        <span class="badge bg-info">Direview</span>
                                                                        @break
                                                                    @case('approved')
                                                                        <span class="badge bg-success">Disetujui</span>
                                                                        @break
                                                                    @case('rejected')
                                                                        <span class="badge bg-danger">Ditolak</span>
                                                                        @break
                                                                @endswitch
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @if($laporan->tanggal_submit)
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Tanggal Submit:</label>
                                                            <p>{{ $laporan->tanggal_submit->format('d F Y H:i') }}</p>
                                                        </div>
                                                        @endif
                                                        @if($laporan->nilai)
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Nilai:</label>
                                                            <p>
                                                                <span class="badge bg-{{ $laporan->nilai >= 80 ? 'success' : ($laporan->nilai >= 70 ? 'warning' : 'danger') }} fs-6">
                                                                    {{ $laporan->nilai }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                        @endif
                                                        @if($laporan->file_laporan)
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">File Laporan:</label>
                                                            <p>
                                                                <a href="{{ Storage::url($laporan->file_laporan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="mdi mdi-download"></i> Download
                                                                </a>
                                                            </p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                @if($laporan->deskripsi)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Deskripsi:</label>
                                                            <p>{{ $laporan->deskripsi }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                
                                                @if($laporan->komentar_pembimbing)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Komentar Pembimbing:</label>
                                                            <div class="alert alert-info">
                                                                {{ $laporan->komentar_pembimbing }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Review Modal -->
                                @if(in_array($laporan->status, ['submitted', 'reviewed']))
                                <div class="modal fade" id="reviewModal{{ $laporan->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Review Laporan Akhir</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form id="reviewForm{{ $laporan->id }}" onsubmit="submitReview(event, {{ $laporan->id }})">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Mahasiswa:</label>
                                                        <p>{{ $laporan->biodata->nama ?? 'Nama tidak tersedia' }} ({{ $laporan->biodata->nim ?? 'NIM tidak tersedia' }})</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Judul Laporan:</label>
                                                        <p>{{ $laporan->judul }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nilai{{ $laporan->id }}" class="form-label">Nilai (0-100):</label>
                                                        <input type="number" class="form-control" id="nilai{{ $laporan->id }}" name="nilai" 
                                                               min="0" max="100" value="{{ $laporan->nilai }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="komentar{{ $laporan->id }}" class="form-label">Komentar:</label>
                                                        <textarea class="form-control" id="komentar{{ $laporan->id }}" name="komentar" rows="4" 
                                                                  placeholder="Berikan komentar atau feedback...">{{ $laporan->komentar_pembimbing }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status{{ $laporan->id }}" class="form-label">Status:</label>
                                                        <select class="form-select" id="status{{ $laporan->id }}" name="status" required>
                                                            <option value="reviewed" {{ $laporan->status == 'reviewed' ? 'selected' : '' }}>Direview</option>
                                                            <option value="approved" {{ $laporan->status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                                            <option value="rejected" {{ $laporan->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Review</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data laporan akhir.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($laporanAkhirs instanceof \Illuminate\Pagination\LengthAwarePaginator && $laporanAkhirs->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Menampilkan {{ $laporanAkhirs->firstItem() }} sampai {{ $laporanAkhirs->lastItem() }} 
                                dari {{ $laporanAkhirs->total() }} data
                            </p>
                        </div>
                        <div>
                            {{ $laporanAkhirs->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Auto submit form when filter changes
        $('#mahasiswa, #status, #tahun').change(function() {
            $(this).closest('form').submit();
        });
    });

    function submitReview(event, laporanId) {
        event.preventDefault();
        
        const form = document.getElementById('reviewForm' + laporanId);
        const formData = new FormData(form);

        fetch(`{{ route('pembimbing.laporan-akhir.review', '') }}/${laporanId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal' + laporanId));
                if (modal) modal.hide();
                
                // Show success message
                showAlert('success', data.message);
                
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memproses review');
        });
    }

    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert alert at the top of the container
        const container = document.querySelector('.container-fluid');
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
</script>
@endpush