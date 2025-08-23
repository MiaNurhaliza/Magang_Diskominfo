@extends('layouts.peserta')

@section('content')
<style>
    @media (max-width: 768px) {
        .container-fluid {
            padding: 10px;
        }
        h2 {
            font-size: 1.5rem;
        }
        .table {
            font-size: 0.9rem;
        }
        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
        .badge {
            font-size: 0.85rem;
        }
    }
    @media (max-width: 576px) {
        .container-fluid {
            padding: 5px;
        }
        h2 {
            font-size: 1.3rem;
        }
        .table {
            font-size: 0.8rem;
        }
        .btn {
            padding: 0.3rem 0.6rem;
            font-size: 0.85rem;
        }
        .badge {
            font-size: 0.8rem;
        }
        .table-responsive {
            margin: 0 -5px;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        {{-- Content --}}
        <div class="col-md-10 p-4">
            <h2>Selamat Datang, {{ Auth::user()->name }}</h2>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5>Absensi</h5>
                            <p class="text-danger mb-0">Absensi di ambil di area Diskominfo kota bukittinggi</p>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#izinModal">
                            <i class="bi bi-plus"></i> Ajukan Izin
                        </button>
                    </div>

                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Absen Pagi</th>
                                <th>Absen Siang</th>
                                <th>Absen Sore</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paginatedAbsensis as $i => $row)
                            <tr>
                                <td>{{ ($paginatedAbsensis->currentPage() - 1) * $paginatedAbsensis->perPage() + $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d F Y') }}</td>
                                <td>
                                    @if($row->izin)
                                        <span class="badge bg-warning">{{ $row->izin->jenis }}</span>
                                    @elseif($row->pagi)
                                        <span class="badge bg-success">{{ $row->pagi }}</span>
                                    @elseif(isset($row->is_today) && $row->is_today && $bolehAbsen)
                                        <button class="btn btn-sm btn-outline-success absen-btn"
                                            data-tanggal="{{ $row->tanggal }}" 
                                            data-sesi="pagi"
                                            data-tanggal-display="{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d F Y') }}">
                                            Absen Masuk
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($row->izin)
                                        <span class="badge bg-warning">{{ $row->izin->jenis }}</span>
                                    @elseif($row->siang)
                                        <span class="badge bg-warning text-dark">{{ $row->siang }}</span>
                                    @elseif(isset($row->is_today) && $row->is_today && $bolehAbsen)
                                        <button class="btn btn-sm btn-outline-warning absen-btn"
                                            data-tanggal="{{ $row->tanggal }}" 
                                            data-sesi="siang"
                                            data-tanggal-display="{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d F Y') }}">
                                            Absen Masuk
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($row->izin)
                                        <span class="badge bg-warning">{{ $row->izin->jenis }}</span>
                                    @elseif($row->sore)
                                        <span class="badge bg-primary">{{ $row->sore }}</span>
                                    @elseif(isset($row->is_today) && $row->is_today && $bolehAbsen)
                                        <button class="btn btn-sm btn-outline-primary absen-btn"
                                            data-tanggal="{{ $row->tanggal }}" 
                                            data-sesi="sore"
                                            data-tanggal-display="{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d F Y') }}">
                                            Absen Masuk
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{-- Pagination --}}
                    @if($paginatedAbsensis->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Menampilkan {{ $paginatedAbsensis->firstItem() }} sampai {{ $paginatedAbsensis->lastItem() }} 
                                dari {{ $paginatedAbsensis->total() }} data
                            </p>
                        </div>
                        <div>
                            {{ $paginatedAbsensis->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Absen --}}
<div class="modal fade" id="absenModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('absensi.store') }}">
            @csrf
            <input type="hidden" name="tanggal" id="tanggalAbsen">
            <input type="hidden" name="sesi" id="sesiAbsen">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ambil Absen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="displayTanggal" class="mb-3 fw-bold"></p>
                    <p id="displaySesi" class="mb-3 text-muted"></p>
                    
                    <div class="form-check mb-2">
                        <input type="radio" class="form-check-input" name="status" value="Hadir" id="hadir" required>
                        <label class="form-check-label" for="hadir">Hadir</label>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <small><strong>Catatan:</strong> Untuk mengajukan izin atau sakit, gunakan tombol "Ajukan Izin" di atas tabel.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Ajukan Izin --}}
<div class="modal fade" id="izinModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('izin.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajukan Izin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="jenisIzin" class="form-label">Jenis Izin</label>
                        <select class="form-select" id="jenisIzin" name="jenis_izin" required>
                            <option value="" selected disabled>Pilih jenis izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin Tidak Masuk">Izin Tidak Masuk</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tanggalIzin" class="form-label">Tanggal Izin</label>
                        <input type="date" class="form-control" id="tanggalIzin" name="tanggal_izin" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="suratKeterangan" class="form-label">Upload Surat Keterangan</label>
                        <input type="file" class="form-control" id="suratKeterangan" name="surat_keterangan">
                        <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max: 2MB)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <p class="mb-0 fw-bold">Keterangan:</p>
                        <ol class="ps-3 mb-0">
                            <li>Silahkan Pilih jenis izin anda</li>
                            <li>Upload Bukti keterangan sesuai dengan jenis izin anda</li>
                            <li>Silahkan isi keterangan alasan izin anda</li>
                        </ol>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Handle absen button clicks
document.addEventListener('DOMContentLoaded', function() {
    const absenButtons = document.querySelectorAll('.absen-btn');
    const absenModal = new bootstrap.Modal(document.getElementById('absenModal'));
    
    absenButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tanggal = this.getAttribute('data-tanggal');
            const sesi = this.getAttribute('data-sesi');
            const tanggalDisplay = this.getAttribute('data-tanggal-display');
            
            document.getElementById('tanggalAbsen').value = tanggal;
            document.getElementById('sesiAbsen').value = sesi;
            document.getElementById('displayTanggal').textContent = tanggalDisplay;
            document.getElementById('displaySesi').textContent = 'Sesi: ' + sesi.charAt(0).toUpperCase() + sesi.slice(1);
            
            // Set default radio button to "Hadir"
            document.getElementById('hadir').checked = true;
            
            absenModal.show();
        });
    });
});

// Tampilkan/sembunyikan field upload surat berdasarkan jenis izin
document.getElementById('jenisIzin').addEventListener('change', function() {
    const suratField = document.getElementById('suratKeterangan');
    const suratLabel = suratField.previousElementSibling;
    const suratHint = suratField.nextElementSibling;
    
    if (this.value === 'Sakit') {
        suratField.required = true;
        suratLabel.textContent = 'Upload Surat Keterangan Sakit (Wajib)';
    } else {
        suratField.required = false;
        suratLabel.textContent = 'Upload Surat Keterangan (Opsional)';
    }
});
</script>
@endsection
