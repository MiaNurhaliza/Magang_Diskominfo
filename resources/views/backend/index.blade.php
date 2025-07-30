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
            @endforeach
        </tbody>
    </table>
</div>
@endsection
