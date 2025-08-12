@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        {{-- Form Upload --}}
        <div class="col-md-9 p-5">
            <h4 class="fw-semibold">Lengkapi Dokumen Pendukung</h4>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


                {{-- Surat Permohonan Magang --}}
         <div class="row mb-4 align-items-center">
    <label class="col-md-4 fw-semibold">Surat Permohonan Magang <span class="text-danger">*</span></label>
    <div class="col-md-6 border p-3 rounded border-primary text-center">
        <div class="mb-2">
            @if(!empty($dokumen?->surat_permohonan))
                <i class="bi bi-file-earmark-check-fill text-secondary" style="font-size: 40px;"></i>
                <p class="fw-semibold text-muted mb-1">Surat_Magang.pdf</p>
                <a href="{{ asset('storage/' . $dokumen->surat_permohonan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    Lihat Surat
                </a>
            @else
                <i class="bi bi-cloud-arrow-up-fill text-primary" style="font-size: 40px;"></i>
                <p class="mb-2 text-muted">Upload Surat</p>
                <input type="file" name="surat_permohonan" class="form-control mb-2 @error('surat_permohonan') is-invalid @enderror">
                @error('surat_permohonan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <p class="text-danger small mt-2">⚠ Ukuran maksimal 5 MB. Format yang diperbolehkan: PDF</p>
            @endif
        </div>
    </div>
</div>



                {{-- Kartu Tanda Mahasiswa/Pelajar --}}
                <div class="row mb-4 align-items-center">
                    <label class="col-md-4 fw-semibold">Kartu tanda Mahasiswa/Pelajar</label>
                    <div class="col-md-6 border p-3 rounded border-primary text-center">
                      <div class="mb-2">
            <i class="bi bi-cloud-arrow-up-fill text-primary" style="font-size: 40px;"></i>
        </div>
                        <p class="mb-2 text-muted">Upload Kartu</p>
                        <input type="file" name="kartu_tanda_mahasiswa" class="form-control mb-2 @error('kartu_tanda_mahasiswa') is-invalid @enderror">
                        @error('kartu_tanda_mahasiswa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!-- <button class="btn btn-primary btn-sm" type="button">Upload Kartu</button> -->
                        <p class="text-danger small mt-2">⚠ Ukuran maksimal 5 MB. Format yang diperbolehkan: PDF</p>
                    </div>
                </div>

                {{-- CV --}}
                <div class="row mb-4 align-items-center">
                    <label class="col-md-4 fw-semibold">CV</label>
                    <div class="col-md-6 border p-3 rounded border-primary text-center">
                        <div class="mb-2">
            <i class="bi bi-cloud-arrow-up-fill text-primary" style="font-size: 40px;"></i>
        </div>
                        <p class="mb-2 text-muted">Upload CV</p>
                        <input type="file" name="cv" class="form-control mb-2 @error('cv') is-invalid @enderror">
                        @error('cv')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!-- <button class="btn btn-primary btn-sm" type="button">Upload CV</button> -->
                        <p class="text-danger small mt-2">⚠ Ukuran maksimal 5 MB. Format yang diperbolehkan: PDF</p>
                    </div>
                </div>

                {{-- Sertifikat Kompetensi --}}
                <div class="row mb- align-items-center">
                    <label class="col-md-4 fw-semibold">Sertifikat Kompetensi</label>
                    <div class="col-md-6 border p-3 rounded border-primary text-center">
                       <div class="mb-2">
            <i class="bi bi-cloud-arrow-up-fill text-primary" style="font-size: 40px;"></i>
        </div>
                        <p class="mb-2 text-muted">Upload Sertifikat</p>
                        <input type="file" name="sertifikat" class="form-control mb-2 @error('sertifikat') is-invalid @enderror">
                        @error('sertifikat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!-- <button class="btn btn-primary btn-sm" type="button">Upload Sertifikat</button> -->
                        <p class="text-danger small mt-2">⚠ Ukuran maksimal 5 MB. Format yang diperbolehkan: PDF</p>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


