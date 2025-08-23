<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Perubahan Jadwal Magang</title>
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
        .warning-badge {
            background: #ffc107;
            color: #212529;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin: 20px 0;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #1465FF;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .schedule-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
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

        <h2 style="color: #1465FF;">📅 Pemberitahuan Perubahan Jadwal Magang</h2>
        
        <p>Yth. <strong>{{ $user->name }}</strong>,</p>
        
        <div class="warning-badge">⚠️ JADWAL DIALIHKAN</div>
        
        <p>Kami ingin memberitahukan bahwa terdapat perubahan jadwal untuk program magang Anda di Dinas Komunikasi dan Informatika Kota Bukittinggi. Pendaftaran magang Anda telah <strong>diterima</strong>, namun jadwal pelaksanaannya perlu dialihkan.</p>

        <div class="info-box">
            <h4 style="margin-top: 0; color: #1465FF;">📋 Detail Pendaftaran:</h4>
            <ul style="margin: 10px 0;">
                <li><strong>Nama:</strong> {{ $biodata->nama_lengkap }}</li>
                <li><strong>NIS/NIM:</strong> {{ $biodata->nis_nim }}</li>
                <li><strong>Sekolah/Universitas:</strong> {{ $biodata->sekolah }}</li>
                <li><strong>Jurusan:</strong> {{ $biodata->jurusan }}</li>
                <li><strong>Jadwal Awal:</strong> {{ \Carbon\Carbon::parse($biodata->tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($biodata->tanggal_selesai)->format('d F Y') }}</li>
            </ul>
        </div>

        @if($biodata->alasan)
        <div class="schedule-box">
            <h4 style="margin-top: 0; color: #856404;">📝 Alasan Perubahan Jadwal:</h4>
            <p style="margin: 10px 0;">{{ $biodata->alasan }}</p>
        </div>
        @endif

        <h4 style="color: #1465FF;">📋 Langkah Selanjutnya:</h4>
        <ol>
            <li>Silakan login ke sistem untuk melihat jadwal baru yang tersedia</li>
            <li>Konfirmasi ketersediaan Anda pada jadwal pengganti</li>
            <li>Hubungi kami jika jadwal pengganti tidak sesuai dengan ketersediaan Anda</li>
            <li>Persiapkan dokumen-dokumen yang diperlukan untuk jadwal baru</li>
        </ol>

        <div class="info-box">
            <h4 style="margin-top: 0; color: #1465FF;">⏰ Penting untuk Diperhatikan:</h4>
            <ul style="margin: 10px 0;">
                <li>Jadwal baru akan segera diinformasikan melalui sistem</li>
                <li>Mohon konfirmasi ketersediaan Anda dalam 3x24 jam</li>
                <li>Jika tidak ada konfirmasi, pendaftaran dapat dibatalkan</li>
                <li>Hubungi kami segera jika ada kendala</li>
            </ul>
        </div>

        <div class="info-box">
            <h4 style="margin-top: 0; color: #1465FF;">📞 Informasi Kontak:</h4>
            <p>Untuk koordinasi lebih lanjut, silakan hubungi kami melalui:</p>
            <ul style="margin: 10px 0;">
                <li><strong>Email:</strong> diskominfo@bukittinggikota.go.id</li>
                <li><strong>Telepon:</strong> (0752) 21234</li>
                <li><strong>Alamat:</strong> Jl. Syech Ibrahim Musa No. 5, Bukittinggi</li>
            </ul>
        </div>

        <p>Kami mohon maaf atas ketidaknyamanan ini dan berterima kasih atas pengertian Anda. Kami tetap menantikan partisipasi Anda dalam program magang di Diskominfo Bukittinggi.</p>

        <p>Salam hormat,<br>
        <strong>Tim Diskominfo Bukittinggi</strong></p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Dinas Komunikasi dan Informatika Kota Bukittinggi</p>
        </div>
    </div>
</body>
</html>
