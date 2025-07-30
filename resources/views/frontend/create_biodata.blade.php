<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Biodata Anda</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
        .btn-biru {
            background-color: #1465FF;
            color: white;
        }

        .btn-biru:hover {
            background-color: #0045a5;
        }
    </style>

</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <!-- <div class="col-md-3 bg-white shadow-sm vh-100 p-4">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="130">
                    <h5 class="mt-3 text-primary">DISKOMINFO<br>BUKITTINGGI</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active bg-primary text-white rounded mb-2 text-center" href="#">Biodata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark text-center mb-2" href="#">Dokumen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark text-center mb-2" href="#">Status Pendaftaran</a>
                    </li>
                </ul>
                <a href="{{ route('logout') }}" class="btn btn-primary w-100 mt-5">Keluar</a>
            </div> -->
  <!-- Sidebar -->
            @include('components.sidebar_daftar')
            <!-- Form -->
            <div class="col-md-10 p-5">
                <h4 class="fw-semibold">Lengkapi Biodata Anda!</h4>

                {{-- Tampilkan pesan sukses --}}
                @if(session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif

                {{-- Tampilkan error validasi --}}
                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('biodata.store') }}" method="POST" class="mt-4">
                    @csrf

                    <div class="mb-3 row">
                        <label for="nama_lengkap" class="col-sm-2 col-form-label fw-semibold">Nama Lengkap</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border-primary" id="nama_lengkap" name="nama_lengkap" placeholder="masukkan nama lengkap">
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nis/nim" class="col-sm-2 col-form-label fw-semibold">NIS/NIM</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border-primary" id="nis/nim" name="nis/nim" placeholder="masukkan NIS/NIM">
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="asal_sekolah/kampus" class="col-sm-2 col-form-label fw-semibold">Asal Sekolah/Kampus</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border-primary" id="asal_sekolah/kampus" name="asal_sekolah/kampus" placeholder="masukkan asal sekolah/kampus">
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jurusan" class="col-sm-2 col-form-label fw-semibold">Jurusan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border-primary" id="jurusan" name="jurusan" placeholder="masukkan jurusan/program studi">
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="matkul_pendukung" class="col-sm-2 col-form-label fw-semibold">Mata Kuliah Pendukung</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border-primary" id="matkul_pendukung" name="matkul_pendukung" placeholder="masukkan mata kuliah pendukung yang sesuai dengan divisi magang">
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tujuan_magang" class="col-sm-2 col-form-label fw-semibold">Tujuan Magang</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border-primary" id="tujuan_magang" name="tujuan_magang" placeholder="masukkan tujuan magang di diskominfo kota bukittinggi">
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama_pembimbing" class="col-sm-2 col-form-label fw-semibold">Nama Pembimbing</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border-primary" id="nama_pembimbing" name="nama_pembimbing" placeholder="masukkan nama pembimbing sekolah/kampus">
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label fw-semibold">Alamat</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control border-primary" id="alamat" name="alamat" placeholder="masukkan alamat">
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="no_hp" class="col-sm-2 col-form-label fw-semibold">Nomor Hp</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control border-primary" id="no_hp" name="no_hp" placeholder="masukkan nomor hp">
                            </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label fw-semibold">Tanggal Mulai s/d Selesai</label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control border-primary" id="tgl_mulai" name="tgl_mulai" value="{{ old('tanggal_mulai') }}">
                            </div>
                            <div class="col-1 text-center">s/d</div>
                            <div class="col-sm-4">
                                <input type="date" class="form-control border-primary" id="tgl_selesai" name="tgl_selesai" value="{{ old('tanggal_selesai') }}">
                            </div>
                        </div>
                      <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    </div>  
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
