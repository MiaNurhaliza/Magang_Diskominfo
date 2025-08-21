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

  <!-- CSS custom (kalau ada) -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    @media (max-width: 768px) {
      #navbar .container-fluid > div {
        width: 100% !important;
      }
      #navLinks {
        gap: 1rem !important;
      }
      .hero-section .container {
        text-align: center;
      }
      .hero-illustration {
        max-width: 80%;
        margin-top: 2rem;
      }
    }
    @media (max-width: 576px) {
      #navbar {
        padding: 0.5rem !important;
      }
      #navLinks {
        gap: 0.5rem !important;
      }
      .hero-section h1 {
        font-size: 2rem;
      }
      .hero-section p {
        font-size: 0.9rem;
      }
    }
  </style>

  <!-- (Opsional) kalau mau tetap pakai file Vite kamu -->
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
            {{-- atau route('login.custom') jika memang ada --}}
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
  <section id="alur" class="bg-light py-5">
    <div class="container text-center">
      <h4 class="fw-bold mb-4">ALUR PENDAFTARAN</h4>
      <p class="mb-5">Pendaftaran magang dilakukan melalui sistem online dengan tahapan sebagai berikut</p>

      <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap mb-3">
        @include('components.step', ['icon' => 'masuk.png', 'title' => 'Masuk', 'desc' => 'Masuk dan bikin akun terlebih dahulu'])
        <div class="step-line-horizontal d-none d-md-block"></div>
        @include('components.step', ['icon' => 'isi_form.png', 'title' => 'Isi form', 'desc' => 'Mengisi form pendaftaran yang telah disediakan.'])
        <div class="step-line-horizontal d-none d-md-block"></div>
        @include('components.step', ['icon' => 'upload_surat.png', 'title' => 'Upload surat', 'desc' => 'Unggah surat permohonan magang dari kampus/sekolah.'])
        <div class="step-line-vertical d-none d-md-block"></div>
      </div>

      <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
        @include('components.step', ['icon' => 'pengumuman.png', 'title' => 'Pengumuman', 'desc' => 'Tunggu surat balasan dari pihak DISKOMINFO.'])
        <div class="step-line-horizontal d-none d-md-block"></div>
        @include('components.step', ['icon' => 'pantau_status.png', 'title' => 'Pantau status', 'desc' => 'Proses verifikasi akan dilakukan oleh pihak DISKOMINFO.'])
        <div class="step-line-horizontal d-none d-md-block"></div>
        @include('components.step', ['icon' => 'tunggu_surat.png', 'title' => 'Tunggu balasan', 'desc' => 'Hasil akan ditampilkan di sistem. Jika diterima, peserta bisa mulai kegiatan magang.'])
      </div>
    </div>
  </section>

  <!-- TENTANG KAMI -->
  <section id="tentang" class="about-section bg-white py-5">
    <div class="container d-flex flex-column flex-md-row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="{{ asset('images/about.png') }}" alt="Tentang Kami" class="img-fluid" style="height:300px;">
      </div>
      <div class="col-md-6">
        <h5 class="fw-bold">Tentang Kami</h5>
        <p>Dinas Komunikasi dan Informatika Kota Bukittinggi (Diskominfo) merupakan instansi pemerintah daerah ...</p>
        <p>Dalam era digital saat ini, Diskominfo terus berkomitmen untuk mendorong transformasi digital ...</p>
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
