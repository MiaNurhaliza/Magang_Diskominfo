@extends('layouts.pembimbing')

@section('title', 'Logbook Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Logbook Mahasiswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                       
                    </ol>
                </div>
            </div>
        </div>
    </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">Data Logbook</h4>
                    
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Hari, Tanggal</th>
                                    <th>Kegiatan</th>
                                    <th>Langkah Kerja</th>
                                    <th>Hasil Akhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logbooks as $logbook)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $logbook->user->biodata->nama_lengkap ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d-m-Y') }}</td>
                                    <td>{{ $logbook->kegiatan }}</td>
                                    <td>{{ $logbook->langkah_kerja ?? '-' }}</td>
                                    <td>
                                        @if ($logbook->hasil_akhir)
                                            <a href="{{ asset('storage/' . $logbook->hasil_akhir) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-image"></i> Lihat Gambar
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('logbook.destroy', $logbook->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Detail Modal -->
                                {{-- <div class="modal fade" id="detailModal{{ $logbook->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Logbook</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Mahasiswa:</label>
                                                            <p>{{ $logbook->biodata->nama_lengkap }} ({{ $logbook->biodata->nim }})</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Tanggal:</label>
                                                            <p>{{ \Carbon\Carbon::parse($logbook->tanggal)->format('d F Y') }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Waktu:</label>
                                                            <p>{{ $logbook->jam_mulai }} - {{ $logbook->jam_selesai }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Status:</label>
                                                            <p>
                                                                @switch($logbook->status)
                                                                    @case('pending')
                                                                        <span class="badge bg-warning">Menunggu Review</span>
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
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Kegiatan:</label>
                                                            <p>{{ $logbook->kegiatan }}</p>
                                                        </div>
                                                        @if($logbook->deskripsi)
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Deskripsi:</label>
                                                            <p>{{ $logbook->deskripsi }}</p>
                                                        </div>
                                                        @endif
                                                        @if($logbook->komentar_pembimbing)
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Komentar Pembimbing:</label>
                                                            <p class="text-muted">{{ $logbook->komentar_pembimbing }}</p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                 --}}
                                                {{-- @if($logbook->status == 'pending')
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h6>Review Logbook</h6>
                                                        <form id="reviewForm{{ $logbook->id }}" onsubmit="submitReview(event, {{ $logbook->id }})">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="komentar{{ $logbook->id }}" class="form-label">Komentar:</label>
                                                                <textarea class="form-control" id="komentar{{ $logbook->id }}" name="komentar" rows="3" placeholder="Berikan komentar atau feedback..."></textarea>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <button type="button" class="btn btn-success" onclick="submitReviewWithStatus({{ $logbook->id }}, 'approved')">
                                                                    <i class="mdi mdi-check"></i> Setujui
                                                                </button>
                                                                <button type="button" class="btn btn-danger" onclick="submitReviewWithStatus({{ $logbook->id }}, 'rejected')">
                                                                    <i class="mdi mdi-close"></i> Tolak
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}
                                @empty 
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data logbook.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination removed since we're using Collection instead of Paginator --}}
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
        $('#mahasiswa, #status, #bulan, #tahun').change(function() {
            $(this).closest('form').submit();
        });
    });

    function reviewLogbook(logbookId, status) {
        if (confirm('Apakah Anda yakin ingin ' + (status === 'approved' ? 'menyetujui' : 'menolak') + ' logbook ini?')) {
            submitReviewWithStatus(logbookId, status);
        }
    }

    function submitReviewWithStatus(logbookId, status) {
        const form = document.getElementById('reviewForm' + logbookId);
        const formData = new FormData(form);
        formData.append('status', status);

        fetch(`{{ route('pembimbing.logbook.review', '') }}/${logbookId}`, {
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
                const modal = bootstrap.Modal.getInstance(document.getElementById('detailModal' + logbookId));
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

    function submitReview(event, logbookId) {
        event.preventDefault();
        // This function can be used for custom review submission if needed
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