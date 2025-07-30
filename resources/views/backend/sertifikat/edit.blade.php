@extends('layouts.backend')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Upload Sertifikat Magang</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.sertifikat.update', $sertifikat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" value="{{ $sertifikat->user->biodata->nama_lengkap }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($sertifikat->tanggal_selesai)->translatedFormat('d-m-Y') }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">Upload Sertifikat (PDF)</label>
                    <input type="file" name="file" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.sertifikat') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
