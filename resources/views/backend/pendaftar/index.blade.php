@extends('layouts.backend')

@section('content')
<div class="container-fluid" style="background-color: #f5f5f5; min-height: 100vh;">
    <h5 class="pt-4 px-4">Selamat Datang, Admin</h5>

    <div class="card mx-4 mt-3 shadow-sm">
        <div class="card-body">
            <h6>Data Pendaftar Magang</h6>
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
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendaftars as $pendaftar)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pendaftar->nama_lengkap }}</td>
                            <td>{{ $pendaftar->asal_sekolah }}</td>
                            <td>{{ $pendaftar->jurusan }}</td>
                            <td>{{ $pendaftar->tanggal_mulai }}</td>
                            <td>{{ $pendaftar->tanggal_selesai }}</td>
                            <td>
                                @if($pendaftar->status == 'Diproses')
                                    <span class="badge bg-warning text-dark">Diproses</span>
                                @elseif($pendaftar->status == 'Diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($pendaftar->status == 'Ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($pendaftar->status == 'Jadwal Dirubah')
                                    <span class="badge bg-info text-dark">Jadwal Dirubah</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $pendaftar->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <form method="POST" action="{{ route('admin.pendaftar.delete', $pendaftar->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Detail --}}
                        <div class="modal fade" id="detailModal{{ $pendaftar->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $pendaftar->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('admin.pendaftar.updateStatus', $pendaftar->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Pendaftar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nama Lengkap:</strong> {{ $pendaftar->nama_lengkap }}</p>
                                            <p><strong>NIS/NIM:</strong> {{ $pendaftar->nis_nim }}</p>
                                            <p><strong>Sekolah/Kampus:</strong> {{ $pendaftar->asal_sekolah }}</p>
                                            <p><strong>Jurusan:</strong> {{ $pendaftar->jurusan }}</p>
                                            <p><strong>Mata Kuliah Pendukung:</strong> {{ $pendaftar->mata_kuliah_pendukung }}</p>
                                            <p><strong>Tujuan Magang:</strong> {{ $pendaftar->tujuan_magang }}</p>
                                            <p><strong>Alamat:</strong> {{ $pendaftar->alamat }}</p>
                                            <p><strong>No. HP:</strong> {{ $pendaftar->no_hp }}</p>
                                            <p><strong>Tanggal Mulai Magang:</strong> {{ $pendaftar->tanggal_mulai }}</p>
                                            <p><strong>Tanggal Selesai Magang:</strong> {{ $pendaftar->tanggal_selesai }}</p>

                                            <hr>
                                            <p><strong>Unduh Dokumen:</strong></p>
                                            @if($pendaftar->dokumen)
                                                <a href="{{ asset('storage/' . $pendaftar->dokumen->surat_permohonan) }}" class="btn btn-outline-warning btn-sm my-1" download>📥 Surat Pengantar</a>
                                                <a href="{{ asset('storage/' . $pendaftar->dokumen->kartu_tanda_mahasiswa) }}" class="btn btn-outline-warning btn-sm my-1" download>📥 KTM</a>
                                                <a href="{{ asset('storage/' . $pendaftar->dokumen->cv) }}" class="btn btn-outline-warning btn-sm my-1" download>📥 CV</a>
                                                @if($pendaftar->dokumen->sertifikat)
                                                    <a href="{{ asset('storage/' . $pendaftar->dokumen->sertifikat) }}" class="btn btn-outline-warning btn-sm my-1" download>📥 Sertifikat</a>
                                                @endif
                                            @else
                                                <p class="text-muted"><em>Dokumen belum tersedia.</em></p>
                                            @endif

                                            <hr>
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Status Pendaftaran</strong></label>
                                                <select name="status" class="form-select">
                                                    <option value="">-- Pilih status --</option>
                                                    <option value="Diproses" {{ $pendaftar->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="Diterima" {{ $pendaftar->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                                    <option value="Ditolak" {{ $pendaftar->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                    <option value="Jadwal Dirubah" {{ $pendaftar->status == 'Jadwal Dirubah' ? 'selected' : '' }}>Jadwal Dirubah</option>
                                                </select>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label"><strong>Alasan</strong></label>
                                                <input type="text" name="alasan" class="form-control" value="{{ $pendaftar->alasan }}">
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label"><strong>Surat Balasan Magang</strong></label>
                                                <input type="file" name="surat_balasan" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data pendaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
