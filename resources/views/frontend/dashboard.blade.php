@extends('layouts.peserta')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        {{-- <div class="col-md-3">
            @include('components.sidebar_user')
        </div> --}}

        {{-- Main content --}}
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h4>Selamat Datang, {{ Auth::user()->name }}</h4>
                    <p>Periode Magang: {{ $biodata->mulai }} s/d {{ $biodata->selesai }}</p>
                    <p>Dinas Komunikasi dan Informatika Kota Bukittinggi</p>

                    {{-- Aturan Magang --}}
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <h5>Pakaian</h5>
                                <ul>
                                    <li>Senin & Selasa: Baju Putih, Celana/rok hitam</li>
                                    <li>Kamis: Batik</li>
                                    <li>Jumat: Baju olahraga</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded">
                                <h5>Absensi</h5>
                                <p>Senin - Kamis: 07.30 - 13.00</p>
                                <p>Jumat: 07.30 - 11.00</p>
                            </div>
                        </div>
                        {{-- Bisa lanjutkan sesuai desain Figma --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
