@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Data Pendaftaran Magang</h4>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Asal Instansi</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftar as $user)
            <tr>
                <td>{{ $user->magang->nama }}</td>
                <td>{{ $user->magang->asal_instansi }}</td>
                <td>{{ $user->status->status ?? 'Belum Ada' }}</td>
                <td>{{ $user->status->catatan_admin ?? '-' }}</td>
                <td>
                    <!-- Tombol Ubah Status -->
                    <form method="POST" action="{{ route('admin.pendaftaran.status', $user->id) }}">
                        @csrf
                        <select name="status" class="form-select mb-2">
                            <option value="diproses" {{ ($user->status->status ?? '') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="diterima" {{ ($user->status->status ?? '') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ ($user->status->status ?? '') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="dijadwalkan ulang" {{ ($user->status->status ?? '') == 'dijadwalkan ulang' ? 'selected' : '' }}>Dijadwalkan Ulang</option>
                        </select>

                        <textarea name="catatan_admin" class="form-control" placeholder="Catatan admin...">{{ $user->status->catatan_admin ?? '' }}</textarea>

                        <button type="submit" class="btn btn-sm btn-primary mt-2">Simpan</button>
                    </form>
                </td>
            </tr>
            <div class="modal fade" id="detailModal{{ $pendaftar->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pendaftar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <dl class="row">
          <dt class="col-sm-4">Nama Lengkap</dt>
          <dd class="col-sm-8">{{ $pendaftar->nama }}</dd>

          <dt class="col-sm-4">NIS/NIM</dt>
          <dd class="col-sm-8">{{ $pendaftar->nis_nim }}</dd>

          <dt class="col-sm-4">Sekolah / Kampus</dt>
          <dd class="col-sm-8">{{ $pendaftar->asal_sekolah }}</dd>

          <dt class="col-sm-4">Jurusan</dt>
          <dd class="col-sm-8">{{ $pendaftar->jurusan }}</dd>

          <dt class="col-sm-4">Tujuan Magang</dt>
          <dd class="col-sm-8">{{ $pendaftar->tujuan_magang }}</dd>

          <dt class="col-sm-4">Unduh CV</dt>
          <dd class="col-sm-8">
            <a href="{{ asset('storage/'.$pendaftar->file_cv) }}" target="_blank" class="btn btn-sm btn-outline-warning">Unduh CV</a>
          </dd>
        </dl>

        <form method="POST" action="{{ route('admin.pendaftar.updateStatus', $pendaftar->id) }}">
          @csrf
          @method('PATCH')

          <div class="mb-3">
            <label for="status" class="form-label">Status Pendaftaran</label>
            <select class="form-select" name="status" required>
              <option value="">- Pilih status pendaftaran -</option>
              <option value="Diproses">Diproses</option>
              <option value="Diterima">Diterima</option>
              <option value="Ditolak">Ditolak</option>
              <option value="Jadwal Dirubah">Jadwal Dirubah</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="alasan" class="form-label">Alasan</label>
            <textarea class="form-control" name="alasan" rows="2"></textarea>
          </div>

          <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach
        </tbody>
    </table>
</div>
@endsection
