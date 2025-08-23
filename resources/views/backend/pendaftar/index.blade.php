@extends('layouts.backend')

@section('content')
<style>
    @media (max-width: 768px) {
        .container-fluid {
            padding: 10px;
        }
        .card {
            margin: 10px !important;
        }
        h5 {
            font-size: 1.1rem;
            padding: 10px !important;
        }
        h6 {
            font-size: 1rem;
        }
        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
        .modal-dialog {
            margin: 0.5rem;
        }
    }
    @media (max-width: 576px) {
        .table {
            font-size: 0.8rem;
        }
        .btn-sm {
            padding: 0.15rem 0.3rem;
            font-size: 0.7rem;
        }
        .badge {
            font-size: 0.7rem;
        }
        .modal-body p {
            font-size: 0.9rem;
        }
        .btn-outline-warning {
            font-size: 0.8rem;
            padding: 0.2rem 0.4rem;
        }
    }
</style>

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
                            <th>Ketersediaan</th>
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
                                    <span class="badge bg-info text-dark">Jadwal Dialihkan</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
    @if($pendaftar->ketersediaan === 'ya')
        <span class="badge bg-success">Menerima</span>
    @elseif($pendaftar->ketersediaan === 'tidak')
        <span class="badge bg-danger">Menolak</span>
    @else
        <span class="badge bg-secondary">Belum Konfirmasi</span>
    @endif
</td>

                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $pendaftar->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>

                                 {{-- Tombol Edit Jadwal --}}
    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editJadwalModal{{ $pendaftar->id }}">
        <i class="bi bi-pencil"></i>
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
                                                    <option value="Jadwal Dirubah" {{ $pendaftar->status == 'Jadwal Dirubah' ? 'selected' : '' }}>Jadwal Dialihkan</option>
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

                        {{-- Modal Edit Jadwal --}}
<div class="modal fade" id="editJadwalModal{{ $pendaftar->id }}" tabindex="-1" aria-labelledby="editJadwalLabel{{ $pendaftar->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.pendaftar.updateJadwal', $pendaftar->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jadwal Magang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal_mulai_{{ $pendaftar->id }}" class="form-label">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai_{{ $pendaftar->id }}" name="tanggal_mulai" class="form-control" value="{{ $pendaftar->tanggal_mulai }}">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_selesai_{{ $pendaftar->id }}" class="form-label">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai_{{ $pendaftar->id }}" name="tanggal_selesai" class="form-control" value="{{ $pendaftar->tanggal_selesai }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $pendaftars->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
