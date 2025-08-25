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
                                    <th>Status</th>
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
                                    {{-- <td>{{ $laporan->created_at->translatedFormat('d-m-Y') }}</td> --}}
                                    <td>{{ $laporan->judul_laporan ?? '-' }}</td>
                                    <td>
                                        @switch($laporan->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Menunggu Review</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">Disetujui</span>
                                                @break
                                            @case('revision')
                                                <span class="badge bg-danger">Perlu Revisi</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">-</span>
                                        @endswitch
                                    </td>
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
                                        <div class="d-flex gap-1 flex-wrap">
                                            @if($laporan->status === 'pending')
                                                <form action="{{ route('pembimbing.laporan-akhir.approve', $laporan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this report?')">
                                                        <i class="bi bi-check-circle"></i> Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('pembimbing.laporan-akhir.revise', $laporan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="revision_note" value="Perlu perbaikan - silakan revisi laporan Anda">
                                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Kirim laporan untuk revisi?')">
                                                        <i class="bi bi-arrow-clockwise"></i> Revisi
                                                    </button>
                                                </form>
                                            @elseif($laporan->status === 'revision')
                                                <form action="{{ route('pembimbing.laporan-akhir.approve', $laporan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this report?')">
                                                        <i class="bi bi-check-circle"></i> Setujui
                                                    </button>
                                                </form>
                                                <span class="badge bg-info">Menunggu Revisi</span>
                                            @elseif($laporan->status === 'approved')
                                                <span class="badge bg-success">Sudah Disetujui</span>
                                            @endif
                                            
                                            {{-- <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $laporan->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button> --}}
                                            
                                            <form action="{{ route('pembimbing.laporan-akhir.destroy', $laporan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- <!-- Detail Modal -->
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
                                </div> --}}

                                
                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $laporan->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Setujui Laporan Akhir</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menyetujui laporan akhir dari <strong>{{ $laporan->biodata->nama_lengkap ?? 'Mahasiswa' }}</strong>?</p>
                                                <p class="text-muted">Setelah disetujui, sertifikat akan otomatis tersedia di admin.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('pembimbing.laporan-akhir.approve', $laporan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Ya, Setujui</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Revise Modal -->
                                <div class="modal fade" id="reviseModal{{ $laporan->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Minta Revisi Laporan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('pembimbing.laporan-akhir.revise', $laporan->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Mahasiswa:</label>
                                                        <p>{{ $laporan->biodata->nama_lengkap ?? 'Mahasiswa' }}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="revision_note{{ $laporan->id }}" class="form-label">Catatan Revisi <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="revision_note{{ $laporan->id }}" name="revision_note" rows="4" 
                                                                  placeholder="Jelaskan bagian yang perlu direvisi..." required>{{ $laporan->revision_note }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data laporan akhir.</td>
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