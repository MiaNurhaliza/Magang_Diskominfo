@extends('layouts.peserta')

@section('content')
<style>
    /* Responsive Styles */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 1.5rem;
        }
        .hero-sub, .hero-org {
            font-size: 0.9rem;
        }
        .section-title {
            font-size: 1.3rem;
        }
        .section-sub {
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.3rem;
        }
        .hero-illus {
            max-width: 80%;
            margin: 0 auto;
            display: block;
        }
        .info-title {
            font-size: 1rem;
        }
        .list-dots li, .time-list li {
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 576px) {
        .hero {
            padding: 1rem;
        }
        .hero-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        .hero-sub, .hero-org {
            font-size: 0.8rem;
        }
        .section-title {
            font-size: 1.2rem;
            margin-top: 1rem;
        }
        .info-card {
            padding: 0.75rem;
        }
        .info-icon {
            width: 2rem;
            height: 2rem;
            font-size: 1rem;
        }
    }
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-9 py-4">

      {{-- HERO --}}
      <div class="hero mb-4">
        <div class="row g-0 align-items-center">
          <div class="col-lg-8">
            <h2 class="hero-title">SELAMAT DATANG,<br>{{ Str::upper(Auth::user()->name) }}</h2>
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

      <h3 class="section-title">Aturan Magang</h3>
      <p class="section-sub">Harap dipatuhi selama masa magang berlangsung.</p>

      {{-- STAGE abu-abu + GRID --}}
      <div class="stage">
        <div class="row g-3 g-md-4">

          <div class="col-md-6">
            <div class="info-card h-100">
              <div class="info-head">
                <div class="info-icon"><i class="bi bi-person"></i></div>
                <h6 class="info-title">Pakaian</h6>
              </div>
              <ul class="list-dots">
                <li><span>Senin & Selasa</span> — <span class="muted">Baju jurusan / putih, celana/rok hitam</span></li>
                <li><span>Rabu</span> — <span class="muted">Hitam Putih</span></li>
                <li><span>Kamis</span> — <span class="muted">Batik</span></li>
                <li><span>Jumat</span> — <span class="muted">Muslim/Kurung</span><br>
                  <span style="color:#ef4444;font-weight:700">✕</span>
                  <span class="muted">Bahan jeans/Levis</span>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-card h-100">
              <div class="info-head">
                <div class="info-icon"><i class="bi bi-pencil"></i></div>
                <h6 class="info-title">Absensi</h6>
              </div>
              <div class="fw-semibold mb-1">Senin s/d Kamis</div>
              <ul class="time-list mb-3">
                <li>⛅ 07.30</li>
                <li>🌞 12.00 – 13.00</li>
                <li>🌖16.00</li>
              </ul>
              <div class="fw-semibold mb-1">Jumat</div>
              <ul class="time-list mb-0">
                <li>⛅07.30</li>
                <li>🌞12.00 – 13.00</li>
                <li>🌖16.00</li>
              </ul>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-card h-100">
              <div class="info-head">
                <div class="info-icon"><i class="bi bi-clock"></i></div>
                <h6 class="info-title">Jam Istirahat</h6>
              </div>
              <ul class="list-dots">
                <li><span>Senin & Selasa</span> — <span class="muted">12.00 – 13.00</span></li>
                <li><span>Jumat</span> — <span class="muted">12.00 – 13.00</span></li>
              </ul>
            </div>
          </div>

          <div class="col-md-6">
            <div class="info-card h-100">
              <div class="info-head">
                <div class="info-icon"><i class="bi bi-clock-history"></i></div>
                <h6 class="info-title">Jam Kerja</h6>
              </div>
              <ul class="list-dots">
                <li><span>Senin s/d Kamis</span> — <span class="muted">07.30 s/d 16.00</span></li>
                <li><span>Jumat</span> — <span class="muted">07.30 s/d 16.30</span></li>
              </ul>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection