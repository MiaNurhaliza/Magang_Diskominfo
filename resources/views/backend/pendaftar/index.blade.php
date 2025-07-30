@extends('backend.layout')
@section('content')

<h3>Data Pendaftar Magang</h3>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Lengkap</th>
      <th>Sekolah</th>
      <th>Jurusan</th>
      <th>Tanggal Mulai</th>
      <th>Tanggal Selesai</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach($pendaftars as $i => $p)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $p->nama_lengkap }}</td>
      <td>{{ $p->sekolah }}</td>
      <td>{{ $p->jurusan }}</td>
      <td>{{ $p->tanggal_mulai->format('d-m-Y') }}</td>
      <td>{{ $p->tanggal_selesai->format('d-m-Y') }}</td>
      <td>
        @if($p->status == 'diproses')
          <span class="badge bg-warning text-dark">🔍 Diproses</span>
        @elseif($p->status == 'diterima')
          <span class="badge bg-success">✔️ Diterima</span>
        @else
          <span class="badge bg-danger">❌ Ditolak</span>
        @endif
      </td>
      <td>
        <a href="{{ route('pendaftar.show', $p->id) }}" class="btn btn-sm btn-primary">👁️</a>
        <form action="{{ route('pendaftar.destroy', $p->id) }}" method="POST" style="display:inline;">
          @csrf @method('DELETE')
          <button type="submit" onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">🗑️</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@endsection
