@extends('backend.layout')
@section('content')

<h4>Detail Pendaftar</h4>
<table class="table">
  <tr><th>Nama</th><td>{{ $pendaftar->nama_lengkap }}</td></tr>
  <tr><th>NISN</th><td>{{ $pendaftar->nisn }}</td></tr>
  <tr><th>Sekolah</th><td>{{ $pendaftar->sekolah }}</td></tr>
  <tr><th>Jurusan</th><td>{{ $pendaftar->jurusan }}</td></tr>
  <tr><th>Kota/Kecamatan</th><td>{{ $pendaftar->kota_kecamatan }}</td></tr>
  <tr><th>Divisi</th><td>{{ $pendaftar->divisi }}</td></tr>
  <tr><th>Alamat</th><td>{{ $pendaftar->alamat }}</td></tr>
  <tr><th>No HP</th><td>{{ $pendaftar->no_hp }}</td></tr>
  <tr><th>Tanggal Mulai</th><td>{{ $pendaftar->tanggal_mulai }}</td></tr>
  <tr><th>Tanggal Selesai</th><td>{{ $pendaftar->tanggal_selesai }}</td></tr>
  <tr><th>Status</th><td>{{ ucfirst($pendaftar->status) }}</td></tr>
  <tr><th>Alasan</th><td>{{ $pendaftar->alasan_status }}</td></tr>
</table>

@if($pendaftar->kartu_pelajar)
<a href="{{ asset('storage/'.$pendaftar->kartu_pelajar) }}" class="btn btn-sm btn-info">Unduh Kartu Pelajar</a>
@endif
@if($pendaftar->cv)
<a href="{{ asset('storage/'.$pendaftar->cv) }}" class="btn btn-sm btn-info">Unduh CV</a>
@endif
@if($pendaftar->sertifikat)
<a href="{{ asset('storage/'.$pendaftar->sertifikat) }}" class="btn btn-sm btn-info">Unduh Sertifikat</a>
@endif

@endsection
