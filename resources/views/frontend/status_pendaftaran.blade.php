@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-3 bg-white border-end min-vh-100">
            @include('components.sidebar_daftar')
        </div>

        {{-- Konten Utama --}}
        <div class="col-md-9 p-5">
            <h4 class="fw-semibold">Pantau Status Pendaftaranmu disini</h4>

            <table class="table table-bordered text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($status as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                        <td>
                            @if($item->status == 'diproses')
                                <span class="text-warning"><i class="bi bi-lightbulb"></i> Diproses</span>
                            @elseif($item->status == 'ditolak')
                                <span class="text-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                            @elseif($item->status == 'diterima')
                                <span class="text-success"><i class="bi bi-check-circle"></i> Diterima</span>
                            @elseif($item->status == 'jadwal_dialihkan')
                                <span class="text-primary"><i class="bi bi-calendar-event"></i> Jadwal Dirubah</span>
                            @endif
                        </td>
                        <td>
                            @if(in_array($item->status, ['diproses', 'ditolak', 'jadwal_dialihkan']))
                                <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#modalAlasan{{ $item->id }}">Lihat Alasan</a>
                            @endif

                            @if($item->status == 'diterima')
                                <a href="{{ route('pendaftaran.unduh_surat', $item->id) }}" class="btn btn-outline-warning btn-sm mb-2">
                                    <i class="bi bi-download"></i> Unduh Surat Balasan
                                </a>
                                <br>
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Lanjutkan</a>
                            @endif
                        </td>
                    </tr>

                    {{-- Modal --}}
                    <div class="modal fade" id="modalAlasan{{ $item->id }}" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Lihat Alasan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Alasan:</strong></p>
                                    <p>{{ $item->alasan ?? '-' }}</p>
                                    @if($item->status == 'jadwal_dialihkan')
                                        <p><strong>Ketersediaan:</strong></p>
                                        <form action="{{ route('pendaftaran.konfirmasi', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="ketersediaan" value="ya" id="ya{{ $item->id }}" required>
                                                <label class="form-check-label" for="ya{{ $item->id }}">Bersedia</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="ketersediaan" value="tidak" id="tidak{{ $item->id }}">
                                                <label class="form-check-label" for="tidak{{ $item->id }}">Tidak</label>
                                            </div>
                                            <div class="mt-3 d-flex justify-content-end">
                                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
