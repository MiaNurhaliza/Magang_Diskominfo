<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Status Pendaftaran Magang</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1465FF;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            color: #1465FF;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .status-badge {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin: 20px 0;
        }
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .reason-box {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">DISKOMINFO BUKITTINGGI</div>
            <p>Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
        </div>

        <h2 style="color: #1465FF;">📋 Pemberitahuan Status Pendaftaran Magang</h2>
        
        <p>Yth. <strong>{{ $user->name }}</strong>,</p>
        
        <div class="status-badge">❌ TIDAK DITERIMA</div>
        
        <p>Terima kasih atas minat Anda untuk mengikuti program magang di Dinas Komunikasi dan Informatika Kota Bukittinggi. Setelah melalui proses seleksi dan evaluasi, kami mohon maaf harus memberitahukan bahwa pendaftaran magang Anda <strong>belum dapat kami terima</strong> pada periode ini.</p>

        <div class="info-box">
            <h4 style="margin-top: 0; color: #856404;">📋 Detail Pendaftaran:</h4>
            <ul style="margin: 10px 0;">
                <li><strong>Nama:</strong> {{ $biodata->nama_lengkap }}</li>
                <li><strong>NIS/NIM:</strong> {{ $biodata->nis_nim }}</li>
                <li><strong>Sekolah/Universitas:</strong> {{ $biodata->sekolah }}</li>
                <li><strong>Jurusan:</strong> {{ $biodata->jurusan }}</li>
                <li><strong>Periode yang Diajukan:</strong> {{ \Carbon\Carbon::parse($biodata->tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($biodata->tanggal_selesai)->format('d F Y') }}</li>
            </ul>
        </div>

        @if($biodata->alasan)
        <div class="reason-box">
            <h4 style="margin-top: 0; color: #721c24;">📝 Alasan:</h4>
            <p style="margin: 10px 0;">{{ $biodata->alasan }}</p>
        </div>
        @endif

        <h4 style="color: #1465FF;">💡 Saran untuk Ke Depan:</h4>
        <ul>
            <li>Anda dapat mendaftar kembali pada periode pendaftaran berikutnya</li>
            <li>Pastikan semua dokumen persyaratan lengkap dan sesuai ketentuan</li>
            <li>Perhatikan kesesuaian jurusan dengan kebutuhan magang di Diskominfo</li>
            <li>Hubungi kami untuk informasi periode pendaftaran selanjutnya</li>
        </ul>

        <div class="info-box">
            <h4 style="margin-top: 0; color: #856404;">📞 Informasi Kontak:</h4>
            <p>Jika ada pertanyaan lebih lanjut, silakan hubungi kami melalui:</p>
            <ul style="margin: 10px 0;">
                <li><strong>Email:</strong> diskominfo@bukittinggikota.go.id</li>
                <li><strong>Telepon:</strong> (0752) 21234</li>
                <li><strong>Alamat:</strong> Jl. Syech Ibrahim Musa No. 5, Bukittinggi</li>
            </ul>
        </div>

        <p>Kami menghargai minat dan antusiasme Anda. Jangan berkecil hati dan tetap semangat untuk kesempatan di masa mendatang!</p>

        <p>Salam hormat,<br>
        <strong>Tim Diskominfo Bukittinggi</strong></p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
        </div>
    </div>
</body>
</html>
