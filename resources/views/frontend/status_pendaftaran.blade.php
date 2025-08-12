@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Konten Utama --}}
        <div class="col-md-9 p-5">
            <h4 class="fw-semibold mb-4">Pantau Status Pendaftaranmu di sini</h4>

            {{-- Pesan sukses --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Tabel Status --}}
            <table class="table table-bordered text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($status as $item)
                    @php
                        // Pastikan status dalam format konsisten
                        $statusVal = strtolower(trim($item->status ?? ''));
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}</td>
                        <td>
                            @switch($statusVal)
                                @case('diproses')
                                    <span class="text-warning"><i class="bi bi-hourglass-split"></i> Diproses</span>
                                    @break
                                @case('ditolak')
                                    <span class="text-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                                    @break
                                @case('diterima')
                                    <span class="text-success"><i class="bi bi-check-circle"></i> Diterima</span>
                                    @break
                                {{-- @case('jadwal_dialihkan')
                                    <span class="text-primary"><i class="bi bi-calendar-event"></i> Jadwal Dialihkan</span>
                                    @break --}}

                                @case('jadwal_dialihkan')
    <span class="text-primary"><i class="bi bi-calendar-event"></i> Jadwal Dialihkan</span>

    {{-- Form konfirmasi --}}
    @if(is_null($item->ketersediaan))
        <form action="{{ route('pendaftaran.konfirmasi', $item->id) }}" method="POST" class="mt-2">
            @csrf
            <button name="ketersediaan" value="ya" class="btn btn-success btn-sm">Diterima</button>
            <button name="ketersediaan" value="tidak" class="btn btn-danger btn-sm">Ditolak</button>
        </form>
    @else
        <div class="mt-1">
            @if($item->ketersediaan === 'ya')
                <span class="badge bg-success">Anda menerima perubahan jadwal</span>
            @else
                <span class="badge bg-danger">Anda menolak perubahan jadwal</span>
            @endif
        </div>
    @endif
    @break


                                @default
                                    <span class="text-muted">-</span>
                            @endswitch
                        </td>
                        <td>
                            {{-- Tombol Lihat Alasan --}}
                            @if(in_array($statusVal, ['diproses', 'ditolak', 'jadwal_dialihkan']))
                                <a href="#" class="text-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalAlasan{{ $item->id }}">
                                    Lihat Alasan
                                </a>
                            @endif

                            {{-- Jika diterima --}}
                            @if($statusVal === 'diterima')
                                <a href="{{ route('pendaftaran.unduh_surat', $item->id) }}" class="btn btn-outline-warning btn-sm mb-2">
                                    <i class="bi bi-download"></i> Unduh Surat Balasan
                                </a>
                                <br>
                                <a href="{{ route('peserta.dashboard') }}" class="btn btn-primary btn-sm">Lanjutkan</a>
                            @endif
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="3">Belum ada data pendaftaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal untuk semua data (diletakkan di luar tabel agar valid HTML) --}}
@foreach($status as $item)
@php
    $statusVal = strtolower(trim($item->status ?? ''));
@endphp
<div class="modal fade" id="modalAlasan{{ $item->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel{{ $item->id }}">Detail Alasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p><strong>Alasan:</strong></p>
                <p>{{ $item->alasan ?? '-' }}</p>

                {{-- Jika jadwal dirubah --}}
                @if($statusVal === 'jadwal_dialihkan')
                    <hr>
                    <p class="mt-3"><strong>Konfirmasi Ketersediaan Jadwal Baru:</strong></p>
                    <form action="{{ route('pendaftaran.konfirmasi', $item->id) }}" method="POST">
                        @csrf
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="ketersediaan" value="ya" id="ya{{ $item->id }}" required>
                            <label class="form-check-label" for="ya{{ $item->id }}">Bersedia</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="ketersediaan" value="tidak" id="tidak{{ $item->id }}">
                            <label class="form-check-label" for="tidak{{ $item->id }}">Tidak Bersedia</label>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
