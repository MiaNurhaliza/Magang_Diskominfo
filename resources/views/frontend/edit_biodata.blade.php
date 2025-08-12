@extends('layouts.app')

@section('content')

<div class="col-md-10 p-5">
    <h4 class="fw-semibold">Edit Biodata Anda</h4>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    {{-- Validasi error --}}
    @if($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

   
     {{-- <div class="text-center mt-3">
            <button type="submit" id="saveButton" class="btn btn-success mt-3 d-none"
                onclick="return confirm('Apakah Anda yakin ingin menyimpan perubahan biodata ini?')">Simpan</button>
        </div> --}}

    <form action="{{ route('biodata.update',$biodata->id) }}" method="POST">
        @csrf
        @method('PUT')

        @php
            function fieldAttr($field, $biodata) {
                return old($field, $biodata->$field ?? '');
            }
        @endphp

        {{-- Field-field --}}
        @foreach ([
            'nama_lengkap' => 'Nama Lengkap',
            'nis_nim' => 'NIS/NIM',
            'asal_sekolah' => 'Asal Sekolah/Kampus',
            'jurusan' => 'Jurusan',
            'matkul_pendukung' => 'Mata Kuliah Pendukung',
            'tujuan_magang' => 'Tujuan Magang',
            'nama_pembimbing' => 'Nama Pembimbing',
            'alamat' => 'Alamat',
            'no_hp' => 'Nomor HP',
        ] as $name => $label)
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label fw-semibold">{{ $label }}</label>
            <div class="col-sm-9">
                <input type="{{ $name == 'no_hp' ? 'number' : 'text' }}" name="{{ $name }}" id="{{ $name }}" 
                    class="form-control border-primary readonly-field" 
                    value="{{ fieldAttr($name, $biodata) }}" readonly>
            </div>
        </div>
        @endforeach

        {{-- Tanggal --}}
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label fw-semibold">Tanggal Mulai s/d Selesai</label>
            <div class="col-sm-4">
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control border-primary readonly-field" 
                    value="{{ old('tanggal_mulai', $biodata->tanggal_mulai) }}" readonly>
            </div>
            <div class="col-1 text-center">s/d</div>
            <div class="col-sm-4">
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control border-primary readonly-field" 
                    value="{{ old('tanggal_selesai', $biodata->tanggal_selesai) }}" readonly>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="text-center mt-3">
            <button type="submit" id="saveButton" class="btn btn-success mt-3 d-none"
                onclick="return confirm('Apakah Anda yakin ingin menyimpan perubahan biodata ini?')">Simpan</button>
        <button type="button" id="editButton" class="btn btn-primary">Edit Biodata</button>
            </div>
    </form>
</div>

{{-- Script --}}
<script>
    document.getElementById('editButton').addEventListener('click', function () {
        const fields = document.querySelectorAll('.readonly-field');
        fields.forEach(field => {
            field.removeAttribute('readonly');
            field.classList.remove('bg-light');
        });

        document.getElementById('saveButton').classList.remove('d-none');
        this.classList.add('d-none');
    });

    // Tambahkan warna abu-abu untuk readonly field saat awal
    window.addEventListener('DOMContentLoaded', () => {
        const fields = document.querySelectorAll('.readonly-field');
        fields.forEach(field => {
            field.classList.add('bg-light');
        });
    });
</script>

@endsection
