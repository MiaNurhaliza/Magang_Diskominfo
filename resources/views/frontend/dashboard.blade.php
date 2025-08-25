@extends('layouts.peserta')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12 py-4">

      {{-- HERO --}}
      <div class="hero mb-4">
        <div class="row g-0 align-items-center">
          <div class="col-lg-8">
            <h2 class="hero-title">SELAMAT DATANG,  {{ Str::upper(Auth::user()->name) }}</h2>
            <p class="hero-sub mb-1">
              Periode Magang :
              <strong>
                @if(isset($biodata) && $biodata->tanggal_mulai && $biodata->tanggal_selesai)
                  {{ \Carbon\Carbon::parse($biodata->tanggal_mulai)->translatedFormat('d M Y') }}
                  s/d
                  {{ \Carbon\Carbon::parse($biodata->tanggal_selesai)->translatedFormat('d M Y') }}
                @else
                  Belum ditentukan
                @endif
              </strong>
            </p>
            <p class="hero-org mb-0">Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
          </div>
          <div class="col-lg-4 position-relative">
            <img class="hero-illus" src="{{ asset('images/gambar-dashboard.png') }}" alt="Ilustrasi">
          </div>
        </div>
      </div>

      <div class="text-center mb-4">
        <h3 class="section-title" style="color: #4285f4; font-weight: bold;">Aturan Magang</h3>
        <p class="section-sub text-muted">Harap dipatuhi selama masa magang berlangsung.</p>
      </div>

      {{-- STAGE abu-abu + GRID --}}
      <div class="stage">
        <div class="row g-3 g-md-4 justify-content-center">
          <div class="col-lg-10 col-xl-8">
            <div class="row g-3 g-md-4">

          <div class="col-md-6 mb-4">
            <div class="info-card h-100 p-4" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
              <div class="info-head d-flex flex-column align-items-center">
                <div class="info-icon mx-auto mb-2" style="width: 60px; height: 60px; background: #e3f2fd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                  <i class="bi bi-person" style="font-size: 24px; color: #1976d2;"></i>
                </div>
                <h6 class="info-title fw-bold">Pakaian</h6>
              </div>
              <div class="text-start">
                <div class="mb-2"><span class="fw-semibold">• Senin dan Selasa</span><br><small class="text-muted">Baju Jurusan</small></div>
                <div class="mb-2"><span class="fw-semibold">• Rabu</span><br><small class="text-muted">Hitam Putih</small></div>
                <div class="mb-2"><span class="fw-semibold">• Kamis</span><br><small class="text-muted">Batik</small></div>
                <div class="mb-2"><span class="fw-semibold">• Jumat</span><br><small class="text-muted">Muslim / Kurung</small></div>
                <div class="text-danger"><span style="color:#ef4444;">✕</span> <small>Memakai Pakaian Berbahan Jeans/Levis</small></div>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="info-card h-100 p-4" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
              <div class="info-head d-flex flex-column align-items-center">
                <div class="info-icon mx-auto mb-2" style="width: 60px; height: 60px; background: #e3f2fd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                  <i class="bi bi-pencil" style="font-size: 24px; color: #1976d2;"></i>
                </div>
                <h6 class="info-title fw-bold">Absensi</h6>
              </div>
              <div class="text-start">
                <div class="mb-3">
                  <span class="fw-semibold">Senin s/d Kamis</span>
                  <div class="ms-3 mt-1">
                    <div><small class="text-muted">⛅ 07.30</small></div>
                    <div><small class="text-muted">🌞 12.00 - 13.00</small></div>
                    <div><small class="text-muted">🌖 16.00</small></div>
                  </div>
                </div>
                <div>
                  <span class="fw-semibold">Jumat</span>
                  <div class="ms-3 mt-1">
                    <div><small class="text-muted">⛅ 07.30</small></div>
                    <div><small class="text-muted">🌞 12.00 - 13.00</small></div>
                    <div><small class="text-muted">🌖 16.30</small></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="info-card h-100 p-4" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
              <div class="info-head d-flex flex-column align-items-center">
                <div class="info-icon mx-auto mb-2" style="width: 60px; height: 60px; background: #e3f2fd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                  <i class="bi bi-clock" style="font-size: 24px; color: #1976d2;"></i>
                </div>
                <h6 class="info-title fw-bold">Jam Istirahat</h6>
              </div>
              <div class="text-start">
                <div class="mb-2"><span class="fw-semibold">• Senin dan Selasa</span><br><small class="text-muted">12.00 s/d 13.00</small></div>
                <div class="mb-2"><span class="fw-semibold">• Jumat</span><br><small class="text-muted">12.00 s/d 13.00</small></div>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="info-card h-100 p-4" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
              <div class="info-head d-flex flex-column align-items-center">
                <div class="info-icon mx-auto mb-2" style="width: 60px; height: 60px; background: #e3f2fd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                  <i class="bi bi-clock-history" style="font-size: 24px; color: #1976d2;"></i>
                </div>
                <h6 class="info-title fw-bold">Jam Kerja</h6>
              </div>
              <div class="text-start">
                <div class="mb-2"><span class="fw-semibold">• Senin s/d Kamis</span><br><small class="text-muted">07.30 s/d 16.00</small></div>
                <div class="mb-2"><span class="fw-semibold">• Jumat</span><br><small class="text-muted">07.30 s/d 16.30</small></div>
              </div>
            </div>
          </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection