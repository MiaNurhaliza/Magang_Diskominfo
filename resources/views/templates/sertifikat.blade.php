<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Magang</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .certificate-container {
            width: 90%;
            height: 90%;
            background: white;
            border: 8px solid #2c5aa0;
            border-radius: 20px;
            position: relative;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .certificate-border {
            border: 3px solid #1e3a8a;
            border-radius: 15px;
            height: 100%;
            padding: 30px;
            position: relative;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 20px;
        }
        
        .title-main {
            font-size: 48px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 10px 0;
            letter-spacing: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .title-sub {
            font-size: 24px;
            color: #374151;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .content {
            text-align: center;
            margin: 40px 0;
        }
        
        .given-to {
            font-size: 18px;
            color: #374151;
            margin-bottom: 20px;
        }
        
        .name {
            font-size: 36px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 20px 0;
            text-decoration: underline;
            text-decoration-color: #2563eb;
            text-underline-offset: 8px;
            letter-spacing: 2px;
        }
        
        .details {
            font-size: 16px;
            line-height: 2;
            color: #374151;
            margin: 30px 0;
        }
        
        .details strong {
            color: #1e3a8a;
            font-weight: 600;
        }
        
        .period {
            font-size: 18px;
            color: #dc2626;
            font-weight: 600;
            margin: 15px 0;
        }
        
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 50px;
            padding: 0 50px;
        }
        
        .signature-section {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-bottom: 2px solid #374151;
            margin: 60px 0 10px 0;
        }
        
        .signature-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }
        
        .date-section {
            text-align: center;
            font-size: 16px;
            color: #374151;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(30, 58, 138, 0.05);
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
        }
        
        .content-wrapper {
            position: relative;
            z-index: 1;
        }
        
        .ornament {
            position: absolute;
            width: 60px;
            height: 60px;
            background: radial-gradient(circle, #2563eb, #1e3a8a);
            border-radius: 50%;
        }
        
        .ornament-top-left {
            top: 20px;
            left: 20px;
        }
        
        .ornament-top-right {
            top: 20px;
            right: 20px;
        }
        
        .ornament-bottom-left {
            bottom: 20px;
            left: 20px;
        }
        
        .ornament-bottom-right {
            bottom: 20px;
            right: 20px;
        }
        
        .certificate-number {
            text-align: right;
            font-size: 16px;
            color: #374151;
            margin-bottom: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="watermark">DISKOMINFO</div>
            
            <!-- Ornaments -->
            <div class="ornament ornament-top-left"></div>
            <div class="ornament ornament-top-right"></div>
            <div class="ornament ornament-bottom-left"></div>
            <div class="ornament ornament-bottom-right"></div>
            
            <div class="content-wrapper">
                <div class="certificate-number">No: {{ $nomor_sertifikat }}</div>
                
                <div class="header">
                    <div class="logo-section">
                        <div class="logo"></div>
                        <div style="text-align: center;">
                            <div style="font-size: 16px; color: #1e3a8a; font-weight: 600;">DINAS KOMUNIKASI DAN INFORMATIKA</div>
                            <div style="font-size: 14px; color: #374151;">KOTA BUKITTINGGI</div>
                        </div>
                        <div class="logo"></div>
                    </div>
                    
                    <div class="title-main">CERTIFICATE</div>
                    <div class="title-sub">PRAKTEK KERJA LAPANGAN</div>
                </div>
                
                <div class="content">
                    <div class="given-to">Diberikan kepada:</div>
                    
                    <div class="name">{{ $nama }}</div>
                    
                    <div class="details">
                        Telah melaksanakan Kegiatan Praktik Kerja Lapangan pada<br>
                        <strong>Dinas Komunikasi dan Informatika Kota Bukittinggi</strong>
                    </div>
                    
                    <div class="period">
                        {{ $tanggal_mulai }} s/d {{ $tanggal_selesai }}
                    </div>
                    
                    <div class="details">
                        Dengan penuh tanggung jawab dan dedikasi yang tinggi.
                    </div>
                </div>
                
                <div class="footer">
                    <div class="signature-section">
                        <div style="margin-bottom: 20px;">
                            Bukittinggi, {{ $tanggal_sekarang }}
                        </div>
                        <div style="font-weight: 600; margin-bottom: 60px;">
                            Kepala Dinas Komunikasi dan Informatika<br>
                            Kota Bukittinggi
                        </div>
                        <div class="signature-line"></div>
                        <div class="signature-title">Nama & Tanda Tangan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
