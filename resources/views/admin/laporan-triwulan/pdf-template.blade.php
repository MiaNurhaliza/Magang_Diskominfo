<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Triwulan {{ $laporan->periode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #333;
        }
        .header h2 {
            font-size: 16px;
            margin: 5px 0;
            color: #666;
        }
        .header p {
            margin: 5px 0;
            color: #888;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
            padding: 5px 0;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
            vertical-align: top;
        }
        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .summary-card {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .summary-card h3 {
            margin: 0 0 5px 0;
            font-size: 24px;
            color: #333;
        }
        .summary-card p {
            margin: 0;
            font-size: 11px;
            color: #666;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .evaluasi-sangat-baik { color: #28a745; font-weight: bold; }
        .evaluasi-baik { color: #17a2b8; font-weight: bold; }
        .evaluasi-cukup { color: #ffc107; font-weight: bold; }
        .evaluasi-perlu-perbaikan { color: #dc3545; font-weight: bold; }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .signature {
            margin-top: 60px;
        }
        .page-break {
            page-break-before: always;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-20 { margin-bottom: 20px; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN TRIWULAN MAGANG MAHASISWA</h1>
        <h2>DINAS KOMUNIKASI DAN INFORMATIKA BUKITTINGGI</h2>
        <p>{{ $laporan->periode }} ({{ $laporan->tanggal_mulai->format('d F Y') }} - {{ $laporan->tanggal_selesai->format('d F Y') }})</p>
    </div>

    <!-- Informasi Laporan -->
    {{-- <div class="info-section">
        <h3>Informasi Laporan</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Periode:</div>
                <div class="info-value">{{ $laporan->periode }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Periode:</div>
                <div class="info-value">{{ $laporan->tanggal_mulai->format('d F Y') }} - {{ $laporan->tanggal_selesai->format('d F Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Mahasiswa:</div>
                <div class="info-value">{{ $laporan->total_mahasiswa }} orang</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Laporan:</div>
                <div class="info-value">{{ ucfirst($laporan->status) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Dibuat oleh:</div>
                <div class="info-value">{{ $laporan->creator->name ?? 'Admin' }}</div>
            </div> --}}
            <div class="info-row">
                <div class="info-label">Tanggal Dibuat:</div>
                <div class="info-value">{{ $laporan->created_at->format('d F Y H:i:s') }}</div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="info-section">
        <h3>Ringkasan Statistik</h3>
        <div class="summary-cards">
            <div class="summary-card">
                <h3>{{ $summary['total_mahasiswa'] }}</h3>
                <p>Total Mahasiswa</p>
            </div>
            <div class="summary-card">
                <h3>{{ $summary['rata_rata_kehadiran'] }}%</h3>
                <p>Rata-rata Kehadiran</p>
            </div>
            <div class="summary-card">
                <h3>{{ $summary['sekolah_terbanyak'] }}</h3>
                <p>Sekolah Terbanyak</p>
            </div>
            <div class="summary-card">
                <h3>{{ $summary['jurusan_terbanyak'] }}</h3>
                <p>Jurusan Terbanyak</p>
            </div>
        </div>
    </div>

    <!-- Distribusi Evaluasi -->
    @if(!empty($summary['evaluasi_counts']))
    <div class="info-section">
        <h3>Distribusi Evaluasi</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori Evaluasi</th>
                    <th>Jumlah Mahasiswa</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary['evaluasi_counts'] as $evaluasi => $count)
                <tr>
                    <td class="evaluasi-{{ strtolower(str_replace(' ', '-', $evaluasi)) }}">{{ $evaluasi }}</td>
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-center">{{ round(($count / $summary['total_mahasiswa']) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Ringkasan Eksekutif -->
    @if($laporan->ringkasan)
    <div class="info-section">
        <h3>Ringkasan Eksekutif</h3>
        <p style="text-align: justify; line-height: 1.6;">{{ $laporan->ringkasan }}</p>
    </div>
    @endif

    <!-- Page Break untuk Detail Mahasiswa -->
    <div class="page-break"></div>

    <!-- Detail Mahasiswa -->
    <div class="info-section">
        <h3>Detail Mahasiswa Magang</h3>
        @if(!empty($laporan->data_mahasiswa))
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Nama</th>
                    <th style="width: 10%;">NIS/NIM</th>
                    <th style="width: 20%;">Asal Sekolah</th>
                    <th style="width: 15%;">Jurusan</th>
                    <th style="width: 10%;">Kehadiran</th>
                    <th style="width: 8%;">Logbook</th>
                    <th style="width: 12%;">Evaluasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan->data_mahasiswa as $index => $mahasiswa)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $mahasiswa['nama'] }}</td>
                    <td class="text-center">{{ $mahasiswa['nis_nim'] }}</td>
                    <td>{{ $mahasiswa['asal_sekolah'] }}</td>
                    <td>{{ $mahasiswa['jurusan'] }}</td>
                    <td class="text-center">{{ $mahasiswa['persentase_kehadiran'] }}%</td>
                    <td class="text-center">{{ $mahasiswa['total_logbook'] }}</td>
                    <td class="text-center evaluasi-{{ strtolower(str_replace(' ', '-', $mahasiswa['evaluasi'])) }}">
                        {{ $mahasiswa['evaluasi'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-center" style="padding: 40px; color: #666;">Tidak ada data mahasiswa untuk periode ini.</p>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Bukittinggi, {{ $generated_at }}</p>
        <div class="signature">
            <p>Kepala Dinas Komunikasi dan Informatika</p>
            <br><br><br>
            <p style="text-decoration: underline; font-weight: bold;">_________________________</p>
            <p>NIP. _________________________</p>
        </div>
    </div>

    <!-- Footer Info -->
    <div style="position: fixed; bottom: 20px; left: 20px; font-size: 10px; color: #888;">
        Laporan ini dibuat secara otomatis oleh Sistem Manajemen Magang Diskominfo Bukittinggi
    </div>
    <div style="position: fixed; bottom: 20px; right: 20px; font-size: 10px; color: #888;">
        Halaman 1 dari 1
    </div>
</body>
</html>