@extends('layouts.backend')

@section('content')
<div class="container-fluid" style="background-color: #f5f5f5; min-height: 100vh;">
    <h5 class="pt-4 px-4">Selamat Datang, Admin</h5>

    <div class="card mx-4 mt-3 shadow-sm">
        <div class="card-body">
            <h6>Data Peserta Magang Aktif</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Sekolah / Kampus Tinggi</th>
                            <th>Jurusan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesertas as $peserta)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $peserta->nama_lengkap }}</td>
                            <td>{{ $peserta->asal_sekolah }}</td>
                            <td>{{ $peserta->jurusan }}</td>
                            <td>{{ $peserta->tanggal_mulai }}</td>
                            <td>{{ $peserta->tanggal_selesai }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $peserta->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="{{route('admin.peserta-aktif.edit', $peserta->id)}}" class="btn btn-sm btn-success"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="#" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Detail --}}
                        <!-- Modal Detail Peserta -->
<div class="modal fade" id="detailModal{{ $peserta->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">DETAIL PESERTA MAGANG</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-2">
          <div class="col-4 fw-bold">Nama Lengkap</div>
          <div class="col-8">{{ $peserta->nama_lengkap }}</div>
        </div>
        <div class="row mb-2">
          <div class="col-4 fw-bold">Sekolah/Kampus</div>
          <div class="col-8">{{ $peserta->asal_sekolah }}</div>
        </div>
        <div class="row mb-2">
          <div class="col-4 fw-bold">Jurusan</div>
          <div class="col-8">{{ $peserta->jurusan }}</div>
        </div>
        <div class="row mb-2">
          <div class="col-4 fw-bold">Alamat</div>
          <div class="col-8">{{ $peserta->alamat ?? '-' }}</div>
        </div>
        <div class="row mb-2">
          <div class="col-4 fw-bold">No. HP</div>
          <div class="col-8">{{ $peserta->no_hp ?? '-' }}</div>
        </div>
        <div class="row mb-2">
          <div class="col-4 fw-bold">Tanggal Mulai Magang</div>
          <div class="col-8">{{ \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d-m-Y') }}</div>
        </div>
        <div class="row mb-2">
          <div class="col-4 fw-bold">Tanggal Selesai Magang</div>
          <div class="col-8">{{ \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d-m-Y') }}</div>
        </div>

        @if ($peserta->dokumen && $peserta->dokumen->surat_pengantar)
        <div class="row mb-2">
          <div class="col-4 fw-bold">Unduh Surat Pengantar</div>
          <div class="col-8">
            <a href="{{ asset('storage/' . $peserta->dokumen->surat_pengantar) }}" class="btn btn-outline-warning btn-sm" target="_blank">
              <i class="bi bi-download"></i> Unduh Surat Pengantar
            </a>
          </div>
        </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@empty
<tr>
                            <td colspan="7" class="text-center text-muted">Belum ada peserta aktif.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $pesertas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
