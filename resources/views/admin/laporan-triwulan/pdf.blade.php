<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Triwulan {{ $laporanTriwulan->periode }}</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 20px;
        }
        
        .logo-section {
            margin-bottom: 15px;
        }
        
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            margin: 10px 0;
        }
        
        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-section {
            margin: 20px 0;
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #2c5aa0;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        
        .info-value {
            flex: 1;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c5aa0;
            margin: 25px 0 15px 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }
        
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .data-table th {
            background-color: #2c5aa0;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
        }
        
        .signature-section {
            margin-top: 30px;
            text-align: right;
        }
        
        .signature-box {
            display: inline-block;
            text-align: center;
            margin-top: 20px;
        }
        
        .signature-line {
            width: 200px;
            border-bottom: 1px solid #333;
            margin: 60px auto 10px auto;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .evaluasi-baik { color: #28a745; font-weight: bold; }
        .evaluasi-cukup { color: #ffc107; font-weight: bold; }
        .evaluasi-kurang { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-section">
            <div class="subtitle">DINAS KOMUNIKASI DAN INFORMATIKA</div>
            <div class="subtitle">KOTA BUKITTINGGI</div>
        </div>
        <div class="title">LAPORAN TRIWULANAN MAGANG</div>
        <div class="subtitle">Periode {{ $laporanTriwulan->periode }}</div>
        <div class="subtitle">{{ $laporanTriwulan->tanggal_mulai->format('d F Y') }} - {{ $laporanTriwulan->tanggal_selesai->format('d F Y') }}</div>
    </div>

    {{-- <div class="info-section">
        <div class="info-row">
            <div class="info-label">Periode Laporan:</div>
            <div class="info-value">{{ $laporanTriwulan->periode }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Periode:</div>
            <div class="info-value">{{ $laporanTriwulan->tanggal_mulai->format('d F Y') }} - {{ $laporanTriwulan->tanggal_selesai->format('d F Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Total Mahasiswa:</div>
            <div class="info-value">{{ $laporanTriwulan->total_mahasiswa }} orang</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status Laporan:</div>
            <div class="info-value">{{ ucfirst($laporanTriwulan->status) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dibuat Tanggal:</div>
            <div class="info-value">{{ $laporanTriwulan->created_at->format('d F Y H:i') }}</div>
        </div>
    </div> --}}

    @if($laporanTriwulan->ringkasan)
    <div class="section-title">RINGKASAN EKSEKUTIF</div>
    <p style="text-align: justify; margin-bottom: 20px;">{{ $laporanTriwulan->ringkasan }}</p>
    @endif

    {{-- <div class="section-title">STATISTIK RINGKAS</div>
    <div class="summary-stats">
        <div class="stat-card">
            <div class="stat-number">{{ $laporanTriwulan->total_mahasiswa }}</div>
            <div class="stat-label">Total Mahasiswa</div>
        </div> --}}
        {{-- <div class="stat-card">
            <div class="stat-number">{{ collect($laporanTriwulan->data_mahasiswa)->where('evaluasi', 'Sangat Baik')->count() + collect($laporanTriwulan->data_mahasiswa)->where('evaluasi', 'Baik')->count() }}</div>
            <div class="stat-label">Performa Baik</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ collect($laporanTriwulan->data_mahasiswa)->avg('persentase_kehadiran') ? round(collect($laporanTriwulan->data_mahasiswa)->avg('persentase_kehadiran'), 1) : 0 }}%</div>
            <div class="stat-label">Rata-rata Kehadiran</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ collect($laporanTriwulan->data_mahasiswa)->sum('total_logbook') }}</div>
            <div class="stat-label">Total Logbook</div>
        </div> --}}
    </div>

    <div class="section-title">DATA DETAIL MAHASISWA MAGANG</div>
    @if(count($laporanTriwulan->data_mahasiswa) > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="15%">Nama Lengkap</th>
                    <th width="10%">NIS/NIM</th>
                    <th width="15%">Asal Sekolah</th>
                    <th width="12%">Jurusan</th>
                    <th width="8%">Mulai</th>
                    <th width="8%">Selesai</th>

                </tr>
            </thead>
            <tbody>
                @foreach($laporanTriwulan->data_mahasiswa as $index => $mahasiswa)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $mahasiswa['nama'] }}</td>
                    <td class="text-center">{{ $mahasiswa['nis_nim'] }}</td>
                    <td>{{ $mahasiswa['asal_sekolah'] }}</td>
                    <td>{{ $mahasiswa['jurusan'] }}</td>
                    <td class="text-center">{{ $mahasiswa['tanggal_mulai'] }}</td>
                    <td class="text-center">{{ $mahasiswa['tanggal_selesai'] }}</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center" style="margin: 30px 0; color: #666;">Tidak ada data mahasiswa untuk periode ini.</p>
    @endif

    <div class="section-title">RINGKASAN</div>
    <div style="margin: 15px 0;">
        <p><strong>Total Mahasiswa Magang: {{ $laporanTriwulan->total_mahasiswa }} orang</strong></p>
    </div>

    <div class="signature-section">
        <div style="margin-top: 40px;">
            <p>Bukittinggi, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-top: 10px; font-weight: bold;">
                Kepala Dinas Komunikasi dan Informatika<br>
                Kota Bukittinggi
            </p>
            <div class="signature-box">
                <div class="signature-line"></div>
                <p style="margin: 0; font-weight: bold;">SURYADI, ST, MM </p><br>
                <p style="margin: 0;">NIP. 198009222010011010</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p style="margin: 0; color: #666;">
            Laporan ini dibuat secara otomatis oleh Sistem Manajemen Magang Diskominfo Bukittinggi<br>
            Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
        </p>
    </div>
</body>
</html>
