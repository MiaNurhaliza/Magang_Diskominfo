@extends('layouts.peserta')

@section('content')
<style>
    @media (max-width: 768px) {
        .container-fluid {
            padding: 10px;
        }
        .card {
            margin: 5px;
        }
        .card-title {
            font-size: 1.2rem;
        }
        .alert {
            font-size: 0.9rem;
            padding: 0.75rem;
        }
        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
        .text-muted {
            font-size: 0.85rem;
        }
    }
    @media (max-width: 576px) {
        .container-fluid {
            padding: 5px;
        }
        .card {
            margin: 2px;
        }
        .card-title {
            font-size: 1.1rem;
        }
        .alert {
            font-size: 0.85rem;
            padding: 0.5rem;
        }
        .btn {
            padding: 0.3rem 0.6rem;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        .text-muted {
            font-size: 0.8rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Sertifikat</h4>

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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal mulai s/d selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($sertifikat)
                                <tr>
                                    <td>1</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($sertifikat->tanggal_mulai)->translatedFormat('d M Y') }} 
                                        s/d 
                                        {{ \Carbon\Carbon::parse($sertifikat->tanggal_selesai)->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('sertifikat.download') }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-download me-1"></i>
                                            Unduh Sertifikat
                                        </a>
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        <i class="fas fa-certificate me-2"></i>
                                        Sertifikat belum tersedia. Silakan hubungi admin.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    @if(!$sertifikat)
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Sertifikat akan tersedia setelah admin memproses dan mengupload sertifikat Anda.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
