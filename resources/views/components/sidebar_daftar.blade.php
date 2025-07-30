
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Biodata Anda</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-biru {
            background-color: #1465FF;
            color: white;
        }

        .btn-biru:hover {
            background-color: #1465FF;
        }
    </style>
</head>
<body>
<div class="col-md-2 bg-white shadow-sm vh-150 p-4 position-relative">
    <div>
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="130">
        <h5 class="mt-3 text-primary">DISKOMINFO<br>BUKITTINGGI</h5>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('biodata') ? 'active bg-primary text-white' : 'text-dark' }} rounded mb-2 text-center" href="{{ route('biodata.create') }}">Biodata</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dokumen') ? 'active bg-primary text-white' : 'text-dark' }} text-center mb-2" href="{{ route('dokumen.create') }}">Dokumen</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('status') ? 'active bg-primary text-white' : 'text-dark' }} text-center mb-2" href="{{route('pendaftaran.status') }}">Status Pendaftaran</a>
        </li>
    </ul>
    </div>
    <a href="{{ route('logout') }}" 
   class="btn btn-biru position-absolute bottom-0 start-0 w-100 d-flex justify-content-between align-items-center px-4 py-2">
    <span>Keluar</span>
    <i class="bi bi-box-arrow-right"></i>
</a>
</div>
    </body>
    </html>
