@extends('layouts.app')

@section('content')



<div class="col-md-10 p-5">
    <h4 class="fw-semibold">Lengkapi Biodata Anda!</h4>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    {{-- Validasi error --}}
    @if($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('biodata.store') }}" method="POST" class="mt-4">
        @csrf

        <div class="mb-3 row">
            <label for="nama_lengkap" class="col-sm-2 col-form-label fw-semibold">Nama Lengkap</label>
            <div class="col-sm-9">
                <input type="text" class="form-control border-primary" id="nama_lengkap" name="nama_lengkap" placeholder="masukkan nama lengkap" value="{{ old('nama_lengkap') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="nis_nim" class="col-sm-2 col-form-label fw-semibold">NIS/NIM</label>
            <div class="col-sm-9">
                <input type="text" class="form-control border-primary" id="nis_nim" name="nis_nim" placeholder="masukkan NIS/NIM" value="{{ old('nis_nim') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="asal_sekolah" class="col-sm-2 col-form-label fw-semibold">Asal Sekolah/Kampus</label>
            <div class="col-sm-9">
                <input type="text" class="form-control border-primary" id="asal_sekolah" name="asal_sekolah" placeholder="masukkan asal sekolah/kampus" value="{{ old('asal_sekolah') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="jurusan" class="col-sm-2 col-form-label fw-semibold">Jurusan</label>
            <div class="col-sm-9">
                <input type="text" class="form-control border-primary" id="jurusan" name="jurusan" placeholder="masukkan jurusan/program studi" value="{{ old('jurusan') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="matkul_pendukung" class="col-sm-2 col-form-label fw-semibold">Mata Kuliah Pendukung</label>
            <div class="col-sm-9">
                <input type="text" class="form-control border-primary" id="matkul_pendukung" name="matkul_pendukung" placeholder="masukkan mata kuliah pendukung" value="{{ old('matkul_pendukung') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="tujuan_magang" class="col-sm-2 col-form-label fw-semibold">Tujuan Magang</label>
            <div class="col-sm-9">
                <input type="text" class="form-control border-primary" id="tujuan_magang" name="tujuan_magang" placeholder="masukkan tujuan magang" value="{{ old('tujuan_magang') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="nama_pembimbing" class="col-sm-2 col-form-label fw-semibold">Nama Pembimbing</label>
            <div class="col-sm-9">
                <input type="text" class="form-control border-primary" id="nama_pembimbing" name="nama_pembimbing" placeholder="masukkan nama pembimbing" value="{{ old('nama_pembimbing') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="alamat" class="col-sm-2 col-form-label fw-semibold">Alamat</label>
            <div class="col-sm-9">
                <input type="text" class="form-control border-primary" id="alamat" name="alamat" placeholder="masukkan alamat" value="{{ old('alamat') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="no_hp" class="col-sm-2 col-form-label fw-semibold">Nomor HP</label>
            <div class="col-sm-9">
                <input type="number" class="form-control border-primary" id="no_hp" name="no_hp" placeholder="masukkan nomor hp" value="{{ old('no_hp') }}">
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label fw-semibold">Tanggal Mulai s/d Selesai</label>
            <div class="col-sm-4">
                <input type="date" class="form-control border-primary" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
            </div>
            <div class="col-1 text-center">s/d</div>
            <div class="col-sm-4">
                <input type="date" class="form-control border-primary" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
            </div>
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </div>
    </form>
</div>

@endsection
