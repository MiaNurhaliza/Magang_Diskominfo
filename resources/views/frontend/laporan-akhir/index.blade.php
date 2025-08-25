@extends('layouts.peserta')

@section('content')
<style>
    @media (max-width: 768px) {
        .main-content {
            padding: 15px !important;
        }
        .card {
            margin: 10px;
        }
        .card-title {
            font-size: 1.2rem;
        }
        .form-label {
            font-size: 0.9rem;
        }
        .form-control {
            font-size: 0.9rem;
        }
        .text-muted {
            font-size: 0.85rem;
        }
        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
        .alert {
            font-size: 0.9rem;
            padding: 0.75rem;
        }
    }
    @media (max-width: 576px) {
        .main-content {
            padding: 10px !important;
        }
        .card {
            margin: 5px;
        }
        .card-title {
            font-size: 1.1rem;
        }
        .form-label {
            font-size: 0.85rem;
        }
        .form-control {
            font-size: 0.85rem;
        }
        .text-muted {
            font-size: 0.8rem;
        }
        .btn {
            padding: 0.3rem 0.6rem;
            font-size: 0.85rem;
        }
        .alert {
            font-size: 0.85rem;
            padding: 0.5rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <div class="main-content p-4">
                @if($laporan)
                    <!-- Tampilan setelah upload -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Laporan Akhir Magang Anda</h4>
                            
                            <!-- Status Laporan -->
                            @if($laporan->status === 'pending')
                                <div class="alert alert-warning d-flex align-items-center mb-3">
                                    <i class="fas fa-clock me-2"></i>
                                    <div>
                                        <strong>Status: Menunggu Review Pembimbing</strong><br>
                                        <small>Laporan Anda sedang direview oleh pembimbing. Mohon tunggu konfirmasi.</small>
                                    </div>
                                </div>
                            @elseif($laporan->status === 'approved')
                                <div class="alert alert-success d-flex align-items-center mb-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <div>
                                        <strong>Status: Laporan Disetujui</strong><br>
                                        <small>Selamat! Laporan Anda telah disetujui pada {{ $laporan->approved_at->format('d-m-Y H:i') }}. Sertifikat akan segera tersedia.</small>
                                    </div>
                                </div>
                            @elseif($laporan->status === 'revision')
                                <div class="alert alert-danger d-flex align-items-center mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        <strong>Status: Perlu Revisi</strong><br>
                                        <small>Laporan Anda perlu direvisi. Silakan perbaiki sesuai catatan di bawah dan upload ulang.</small>
                                    </div>
                                </div>
                                
                                @if($laporan->revision_note)
                                    <div class="alert alert-info mb-3">
                                        <h6><i class="fas fa-sticky-note me-2"></i>Catatan Revisi dari Pembimbing:</h6>
                                        <p class="mb-0">{{ $laporan->revision_note }}</p>
                                    </div>
                                @endif
                            @endif

                            <div class="mb-3 d-flex align-items-center">
                                <i class="fas fa-file-alt me-2"></i>
                                <strong>Judul Laporan:</strong> {{ $laporan->judul_laporan }}
                            </div>

                            <div class="mb-3 d-flex align-items-center">
                                <i class="fas fa-calendar me-2"></i>
                                <strong>Tanggal Upload:</strong> {{ $laporan->created_at->format('d-m-Y H:i') }}
                            </div>

                            <div class="d-flex gap-2 mb-3">
                                <a href="{{ route('laporan-akhir.download', 'laporan') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>
                                    Lihat Laporan
                                </a>
                                <a href="{{ route('laporan-akhir.download', 'nilai') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-eye me-1"></i>
                                    Lihat Nilai
                                </a>
                            </div>

                            @if($laporan->status === 'revision')
                                <div class="border-top pt-3">
                                    <h5 class="mb-3">Upload Ulang Laporan</h5>
                                    <form action="{{ route('laporan-akhir.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="judul_laporan" class="form-label">Judul Laporan</label>
                                            <input type="text" class="form-control @error('judul_laporan') is-invalid @enderror" 
                                                   id="judul_laporan" name="judul_laporan" value="{{ old('judul_laporan', $laporan->judul_laporan) }}" required>
                                            @error('judul_laporan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>



                                        <div class="mb-3">
                                            <label for="file_laporan" class="form-label">Upload Laporan Baru</label>
                                            <input type="file" class="form-control @error('file_laporan') is-invalid @enderror" 
                                                   id="file_laporan" name="file_laporan" accept=".pdf" required>
                                            <div class="form-text text-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Ukuran maksimal 5 MB format yang di perbolehkan: PDF
                                            </div>
                                            @error('file_laporan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="file_nilai_magang" class="form-label">Penilaian Nilai Magang Baru</label>
                                            <input type="file" class="form-control @error('file_nilai_magang') is-invalid @enderror" 
                                                   id="file_nilai_magang" name="file_nilai_magang" accept=".pdf" required>
                                            <div class="form-text text-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Ukuran maksimal 5 MB format yang di perbolehkan: PDF
                                            </div>
                                            @error('file_nilai_magang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-upload me-1"></i>
                                            Upload Ulang Laporan
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                @else
                    <!-- Form upload pertama kali -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-1">Laporan Akhir Magang</h4>
                            <p class="text-muted mb-4">Laporan adalah hasil akhir pembimbing industri</p>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('laporan-akhir.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="judul_laporan" class="form-label">Judul Laporan</label>
                                    <input type="text" class="form-control @error('judul_laporan') is-invalid @enderror" 
                                           id="judul_laporan" name="judul_laporan" value="{{ old('judul_laporan') }}" required>
                                    @error('judul_laporan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="pembimbing_industri" class="form-label">Pembimbing Industri</label>
                                    <input type="text" class="form-control @error('pembimbing_industri') is-invalid @enderror" 
                                           id="pembimbing_industri" name="pembimbing_industri" value="{{ old('pembimbing_industri') }}" required>
                                    @error('pembimbing_industri')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                <div class="mb-3">
                                    <label for="file_laporan" class="form-label">Upload Laporan</label>
                                    <input type="file" class="form-control @error('file_laporan') is-invalid @enderror" 
                                           id="file_laporan" name="file_laporan" accept=".pdf" required>
                                    <div class="form-text text-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Ukuran maksimal 5 MB format yang di perbolehkan: PDF
                                    </div>
                                    @error('file_laporan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="file_nilai_magang" class="form-label">Penilaian Nilai Magang</label>
                                    <input type="file" class="form-control @error('file_nilai_magang') is-invalid @enderror" 
                                           id="file_nilai_magang" name="file_nilai_magang" accept=".pdf" required>
                                    <div class="form-text text-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Ukuran maksimal 5 MB format yang di perbolehkan: PDF
                                    </div>
                                    @error('file_nilai_magang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-upload me-1"></i>
                                    Upload Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
