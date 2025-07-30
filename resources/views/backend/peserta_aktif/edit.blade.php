@extends('layouts.backend')

@section('content')
<div class="container mt-5">
    <h4>Edit Data Peserta Magang</h4>
    <form action="{{ route('admin.peserta-aktif.update', $peserta->id) }}" method="POST">
        @csrf
        @method('PATCH')
        
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ $peserta->nama_lengkap }}">
        </div>

        <div class="mb-3">
            <label>Sekolah/Kampus</label>
            <input type="text" name="asal_sekolah" class="form-control" value="{{ $peserta->asal_sekolah }}">
        </div>

        <div class="mb-3">
            <label>Jurusan</label>
            <input type="text" name="jurusan" class="form-control" value="{{ $peserta->jurusan }}">
        </div>

        <div class="mb-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ $peserta->tanggal_mulai }}">
        </div>

        <div class="mb-3">
            <label>Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ $peserta->tanggal_selesai }}">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.peserta-aktif') }}" class="btn btn-secondary">Kembali</a>
    </form> 
</div>
@endsection
