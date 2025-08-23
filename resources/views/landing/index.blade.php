<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIMADIS – Landing</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons (opsional) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <!-- CSS custom -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <style>
    /* --- Responsive navbar / hero --- */
    @media (max-width: 768px) {
      #navbar .container-fluid > div { width: 100% !important; }
      #navLinks { gap: 1rem !important; }
      .hero-section .container { text-align: center; }
      .hero-illustration { max-width: 80%; margin-top: 2rem; }
    }
    @media (max-width: 576px) {
      #navbar { padding: 0.5rem !important; }
      #navLinks { gap: 0.5rem !important; }
      .hero-section h1 { font-size: 2rem; }
      .hero-section p { font-size: 0.9rem; }
    }

    /* --- Step cards (bantu stabilkan tinggi untuk penempatan garis) --- */
    .step-card{ min-height: 170px; } /* boleh disesuaikan */

    /* --- Konektor horizontal (garis kecil di antara kartu) --- */
    .step-connector{
      height: 2px;
      border-radius: 1px;
      background: linear-gradient(90deg, #1976d2, #42a5f5);
      width: 40px;
    }
    /* Konektor arah kebalikan (untuk baris bawah yang kanan -> kiri) */
    .step-connector.rev{
      background: linear-gradient(90deg, #42a5f5, #1976d2);
    }

    /* --- Konektor vertikal di bawah kartu kanan baris atas --- */
    .step-connector-v{
      width: 2px;
      height: 56px;               /* panjang garis vertikal */
      background: linear-gradient(180deg, #1976d2, #42a5f5);
      border-radius: 1px;
      display: inline-block;
    }

    /* Sembunyikan semua konektor di layar kecil */
    @media (max-width: 991.98px){
      .step-connector,
      .step-connector-v { display: none !important; }
    }
  </style>

  {{-- @vite(['resources/css/app.css','resources/js/app.js']) --}}
</head>
<body class="bg-white">

  <!-- NAVBAR -->
  <nav id="navbar" class="navbar navbar-expand-lg p-0 sticky-top bg-white">
    <div class="container-fluid d-flex align-items-center p-0">
      <div class="bg-white d-flex align-items-center ps-4 py-2 flex-grow-1" style="width:60%;">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="me-3" style="height:80px;">
        <div class="border-start ps-3">
          <h6 class="mb-1 fw-bold text-uppercase">PEMERINTAH KOTA BUKITTINGGI</h6>
          <p class="mb-0 small text-uppercase">PROVINSI SUMATERA BARAT</p>
        </div>
      </div>

      <div class="bg-white text-black d-flex justify-content-end align-items-center pe-4 py-2 flex-grow-1" style="width:40%;">
        <ul class="navbar-nav flex-row gap-4" id="navLinks">
          <li class="nav-item"><a class="nav-link text-black fw-semibold" href="#beranda">Beranda</a></li>
          <li class="nav-item"><a class="nav-link text-black fw-semibold" href="#alur">Alur</a></li>
          <li class="nav-item"><a class="nav-link text-black fw-semibold" href="#tentang">Tentang Kami</a></li>
          <li class="nav-item">
            <a class="btn btn-primary" href="{{ route('login') }}">Masuk</a>
           
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section id="beranda" class="hero-section bg-white py-5 position-relative overflow-hidden">
    <div class="container d-flex flex-column flex-md-row justify-content-between">
      <div class="col-md-8">
        <h1 class="fw-bold">SIMADIS</h1>
        <p class="fs-5">Sistem magang Diskominfo</p>
        <p>
          Kesempatan magang bersama kami! Diskominfo Kota Bukittinggi membuka program magang bagi siswa SMK dan
          mahasiswa dari berbagai jurusan. Peserta tidak perlu memilih posisi, karena penempatan akan ditentukan
          oleh Diskominfo setelah dinyatakan diterima.
        </p>

        <div class="d-flex gap-3 mt-3 align-items-center">
          <div class="text-center">
            <img src="{{ asset('images/icon_magang.png') }}" alt="Magang" style="height:37px">
            <p class="small" style="font-size:12px">MAGANG</p>
          </div>
          <img src="{{ asset('images/panah.png') }}" style="height:24px;">
          <div class="text-center">
            <img src="{{ asset('images/icon_latihan.png') }}" alt="Latihan" style="height:37px">
            <p class="small" style="font-size:12px">LATIHAN</p>
          </div>
          <img src="{{ asset('images/panah.png') }}" style="height:24px;">
          <div class="text-center">
            <img src="{{ asset('images/icon_pengalaman.png') }}" alt="Pengalaman" style="height:37px">
            <p class="small" style="font-size:12px">PENGALAMAN</p>
          </div>
          <img src="{{ asset('images/panah.png') }}" style="height:24px;">
          <div class="text-center">
            <img src="{{ asset('images/icon_kerja.png') }}" alt="Kerja" style="height:37px">
            <p class="small" style="font-size:12px">KERJA</p>
          </div>
        </div>
      </div>

      <div class="col-md-4 d-flex justify-content-center">
        <img src="{{ asset('images/hero.png') }}" alt="Ilustrasi" class="img-fluid hero-illustration" style="z-index:1;">
      </div>
    </div>
  </section>

  <!-- ALUR PENDAFTARAN -->
  <section id="alur" class="py-5 position-relative" style="background: white;">
    <!-- Garis horizontal biru atas -->
    <!-- Garis bergelombang biru atas (seperti di Figma) -->
<div class="wave-top position-absolute top-0 start-0 w-100 ">
  <svg viewBox="0 0 1200 80" preserveAspectRatio="none" style="width:100%; height:100%; display:block;">
    <!-- garis utama -->
    <path
      d="M0,50 C240,24 480,50 720,50 C960,50 1200,58 1500,-50"
      fill="none"
      stroke="#1465FF"
      stroke-width="4"
      stroke-linecap="round"/>
    
  </svg>
</div>


    <div class="container position-relative" style="z-index: 2;">
      <div class="text-center mb-5" style="padding-top: 40px;">
        <h2 class="fw-bold mb-3 fs-5  " style="color: black;">ALUR PENDAFTARAN</h2>
        <p class="text-muted fs-9">Pendaftaran magang dilakukan melalui sistem online dengan tahapan sebagai berikut</p>
      </div>

      <!-- Baris 1 -->
      <div class="row justify-content-center align-items-center mb-4">
        <!-- Kartu 1 -->
        <div class="col-lg-2 col-md-3 col-4 mb-3">
          <div class="text-center">
            <div class="step-card bg-white rounded-3 shadow-sm p-3 mb-2 position-relative" style="border: 1px solid #e3f2fd;">
              <div class="step-icon-wrapper bg-primary rounded-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="bi bi-box-arrow-in-right text-white" style="font-size: 1.2rem;"></i>
              </div>
              <h6 class="fw-bold text-primary mb-1 fs-9">Masuk</h6>
              <p class="text-muted" style="font-size: 0.75rem; margin-bottom: 0;">Masuk dan bikin akun terlebih dahulu</p>
            </div>
          </div>
        </div>

        <!-- Garis horizontal 1 -->
        <div class="col-lg-1 col-md-1 d-none d-md-flex justify-content-center">
          <div class="step-connector"></div>
        </div>

        <!-- Kartu 2 -->
        <div class="col-lg-2 col-md-3 col-4 mb-3">
          <div class="text-center">
            <div class="step-card bg-white rounded-3 shadow-sm p-3 mb-2 position-relative" style="border: 1px solid #e3f2fd;">
              <div class="step-icon-wrapper bg-primary rounded-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="bi bi-clipboard-check text-white" style="font-size: 1.2rem;"></i>
              </div>
              <h6 class="fw-bold text-primary mb-1 fs-9">Isi form pendaftaran</h6>
              <p class="text-muted" style="font-size: 0.75rem; margin-bottom: 0;">Mengisi form pendaftaran yang telah disediakan</p>
            </div>
          </div>
        </div>

        <!-- Garis horizontal 2 -->
        <div class="col-lg-1 col-md-1 d-none d-md-flex justify-content-center">
          <div class="step-connector"></div>
        </div>

        <!-- Kartu 3 (kanan atas) -->
        <div class="col-lg-2 col-md-3 col-4 mb-3">
          <div class="text-center">
            <div class="step-card bg-white rounded-3 shadow-sm p-3 mb-2 position-relative" style="border: 1px solid #e3f2fd;">
              <div class="step-icon-wrapper bg-primary rounded-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="bi bi-cloud-upload text-white" style="font-size: 1.2rem;"></i>
              </div>
              <h6 class="fw-bold text-primary mb-1 fs-9">Upload surat permohonan</h6>
              <p class="text-muted" style="font-size: 0.75rem; margin-bottom: 0;">Unggah surat permohonan magang dari kampus/sekolah</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Garis vertikal di bawah kartu kanan atas (posisi sesuai grid) -->
      <div class="row align-items-start mb-4 d-none d-lg-flex">
        <div class="col-lg-2"></div>  <!-- kosong sejajar kartu 1 -->
        <div class="col-lg-1"></div>  <!-- kosong sejajar garis 1 -->
        <div class="col-lg-2"></div>  <!-- kosong sejajar kartu 2 -->
        <div class="col-lg-1"></div>  <!-- kosong sejajar garis 2 -->
        <div class="col-lg-6 d-flex justify-content-center">
          <span class="step-connector-v"></span>
        </div>
      </div>

      <!-- Baris 2 (kanan -> kiri) -->
      <div class="row justify-content-center align-items-center flex-row-reverse">
        <!-- Kartu kanan bawah -->
        <div class="col-lg-2 col-md-3 col-4 mb-3">
          <div class="text-center">
            <div class="step-card bg-white rounded-3 shadow-sm p-3 mb-2 position-relative" style="border: 1px solid #e3f2fd;">
              <div class="step-icon-wrapper bg-primary rounded-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="bi bi-hourglass-split text-white" style="font-size: 1.2rem;"></i>
              </div>
              <h6 class="fw-bold text-primary mb-1 fs-9">Tunggu surat balasan</h6>
              <p class="text-muted" style="font-size: 0.75rem; margin-bottom: 0;">Tunggu surat balasan dari pihak DISKOMINFO</p>
            </div>
          </div>
        </div>

        <!-- Garis horizontal bawah 1 (arah balik) -->
        <div class="col-lg-1 col-md-1 d-none d-md-flex justify-content-center">
          <div class="step-connector rev"></div>
        </div>

        <!-- Kartu tengah bawah -->
        <div class="col-lg-2 col-md-3 col-4 mb-3">
          <div class="text-center">
            <div class="step-card bg-white rounded-3 shadow-sm p-3 mb-2 position-relative" style="border: 1px solid #e3f2fd;">
              <div class="step-icon-wrapper bg-primary rounded-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="bi bi-search text-white" style="font-size: 1.2rem;"></i>
              </div>
              <h6 class="fw-bold text-primary mb-1 fs-9">Pantau Status Pendaftaran</h6>
              <p class="text-muted" style="font-size: 0.75rem; margin-bottom: 0;">Proses verifikasi akan dilakukan oleh pihak DISKOMINFO</p>
            </div>
          </div>
        </div>

        <!-- Garis horizontal bawah 2 (arah balik) -->
        <div class="col-lg-1 col-md-1 d-none d-md-flex justify-content-center">
          <div class="step-connector rev"></div>
        </div>

        <!-- Kartu kiri bawah -->
        <div class="col-lg-2 col-md-3 col-4 mb-3">
          <div class="text-center">
            <div class="step-card bg-white rounded-3 shadow-sm p-3 mb-2 position-relative" style="border: 1px solid #e3f2fd;">
              <div class="step-icon-wrapper bg-primary rounded-2 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="bi bi-megaphone text-white" style="font-size: 1.2rem;"></i>
              </div>
              <h6 class="fw-bold text-primary mb-1 fs-9">Pengumuman hasil</h6>
              <p class="text-muted" style="font-size: 0.75rem; margin-bottom: 0;">Hasil akan ditampilkan di sistem. Jika diterima, peserta bisa mulai kegiatan magang</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Garis horizontal biru bawah -->
    <!-- Garis bergelombang biru bawah -->
<div class="wave-bottom position-absolute bottom-0 start-0 w-100">
  <svg viewBox="0 0 1200 80" preserveAspectRatio="none" style="width:100%; height:100%; display:block;">
    <!-- trik: flip vertikal supaya arah lengkungnya kebawah -->
    <g transform="scale(1,-1) translate(0,-80)">
      <!-- garis utama -->
      <path
        d="M0,50 C240,24 480,50 720,50 C960,50 1200,58 1500,-50"
        fill="none"
        stroke="#1465FF"
        stroke-width="4"
        stroke-linecap="round"/>
      
      
    </g>
  </svg>
</div>

  </section>

  <!-- TENTANG KAMI -->
  <section id="tentang" class="about-section bg-white py-5">
    <div class="container d-flex flex-column flex-md-row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="{{ asset('images/about.png') }}" alt="Tentang Kami" class="img-fluid" style="height:300px;">
      </div>
      <div class="col-md-6">
        <h5 class="fw-bold ">Tentang Kami</h5>
        <p>Dinas Komunikasi dan Informatika Kota Bukittinggi (Diskominfo) merupakan instansi pemerintah daerah yang bertugas dalam bidang komunikasi, informatika, persandian, dan statistik. Diskominfo memiliki peran penting dalam mendukung transparansi informasi, pengelolaan data pemerintah, serta penyebaran informasi publik kepada masyarakat.</p>
        <p>Dalam era digital saat ini, Diskominfo terus berkomitmen untuk mendorong transformasi digital di lingkungan pemerintahan dan masyarakat. Melalui berbagai program dan layanan berbasis teknologi, Diskominfo hadir sebagai garda terdepan dalam pengembangan sistem informasi dan komunikasi di Kota Bukittinggi.</p>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-primary text-white py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between">
      <div>
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:50px;">
        <p class="mt-2 mb-0">Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
      </div>
      <div>
        <p class="fw-bold">Alamat:</p>
        <p>Jl. Veteran No. 1, Gulai Bancah, Kec. MKS, Kota Bukittinggi, Sumbar</p>
        <p>Email: <a href="mailto:diskominfo@bukittinggikota.go.id" class="text-white">diskominfo@bukittinggikota.go.id</a></p>
        <p>Instagram: @diskominfo.bukittinggi</p>
      </div>
      <div>
        <p class="fw-bold">Navigasi</p>
        <ul class="list-unstyled">
          <li><a href="#alur" class="text-white">Alur Pendaftaran</a></li>
          <li><a href="#tentang" class="text-white">Tentang Kami</a></li>
          <li><a href="{{ route('login') }}" class="text-white">Masuk</a></li>
        </ul>
      </div>
    </div>
    <div class="text-center mt-3 small">© 2025 Diskominfo Bukittinggi. All rights reserved.</div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
