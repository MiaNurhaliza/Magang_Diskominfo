@extends('layouts.backend')

@section('content')
<style>
    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }
        h4 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .form-control {
            font-size: 0.9rem;
        }
        label {
            font-size: 0.9rem;
        }
        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
    }
    @media (max-width: 576px) {
        .container {
            padding: 10px;
        }
        h4 {
            font-size: 1.3rem;
        }
        .form-control {
            font-size: 0.85rem;
        }
        label {
            font-size: 0.85rem;
        }
        .btn {
            padding: 0.3rem 0.6rem;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
    }
</style>

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
