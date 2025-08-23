<?php

namespace App\Services;

use App\Models\Biodata;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SertifikatService
{
    public function generateSertifikat($userId)
    {
        // Ambil data user dan biodata
        $user = User::find($userId);
        $biodata = Biodata::where('user_id', $userId)->first();
        
        if (!$user || !$biodata) {
            throw new \Exception('Data user atau biodata tidak ditemukan');
        }
        
        // Path template PDF kosong
        $templatePath = storage_path('app/templates/sertifikat.pdf');
        
        if (file_exists($templatePath)) {
            // Gunakan template PDF jika tersedia
            return $this->generateSertifikatFromPdfTemplate($userId);
        } else {
            // Fallback ke HTML template
            return $this->generateSertifikatFromHtml($userId);
        }
    }

    public function generateSertifikatFromPdfTemplate($userId)
    {
        // Cek apakah FPDI tersedia
        if (!class_exists('\setasign\Fpdi\Fpdi')) {
            // Jika FPDI tidak tersedia, gunakan HTML template
            return $this->generateSertifikatFromHtml($userId);
        }

        $user = User::find($userId);
        $biodata = Biodata::where('user_id', $userId)->first();
        
        if (!$user || !$biodata) {
            throw new \Exception('Data user atau biodata tidak ditemukan');
        }
        
        // Path template PDF kosong
        $templatePath = storage_path('app/templates/sertifikat.pdf');
        
        // Buat PDF baru menggunakan template
        $pdf = new \setasign\Fpdi\Fpdi();
        
        // Import halaman pertama dari template
        $pageCount = $pdf->setSourceFile($templatePath);
        $templateId = $pdf->importPage(1);
        
        // Tambah halaman baru
        $pdf->AddPage('L'); // Landscape
        $pdf->useTemplate($templateId);
        
        // Set font untuk menambahkan text
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(0, 0, 0);
        
        // Format tanggal
        $tanggalMulai = \Carbon\Carbon::parse($biodata->tanggal_mulai)->translatedFormat('d F Y');
        $tanggalSelesai = \Carbon\Carbon::parse($biodata->tanggal_selesai)->translatedFormat('d F Y');
        
        // Generate nomor sertifikat
        $nomorSertifikat = $this->generateNomorSertifikat();
        
        // Nomor sertifikat
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(0, 85); // Posisi nomor sertifikat
        $pdf->Cell(0, 10, 'No: ' . $nomorSertifikat, 0, 1, 'C');
        
        // Koordinat untuk nama (disesuaikan agar lebih ke tengah)
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetXY(0, 110); // Geser ke kiri dari 105 ke 80
        $pdf->Cell(0, 10, strtoupper($biodata->nama_lengkap), 0, 1, 'C');
        
        // Set font untuk informasi lainnya
        $pdf->SetFont('Arial', '', 14);
        
        // Periode magang
        $periode = $tanggalMulai . ' s/d ' . $tanggalSelesai;
        $pdf->SetXY(0, 140); // Geser ke kiri dari 105 ke 80
        $pdf->Cell(0, 10, $periode, 0, 1, 'C');
        
        // // Asal sekolah
        // $pdf->SetXY(105, 155);
        // $pdf->Cell(0, 10, $biodata->asal_sekolah, 0, 1, 'C');
        
        // // Jurusan
        // $pdf->SetXY(105, 170);
        // $pdf->Cell(0, 10, $biodata->jurusan, 0, 1, 'C');
        
        // Generate nama file
        $fileName = 'sertifikat_' . str_replace(' ', '_', strtolower($user->name)) . '_' . date('Y-m-d') . '.pdf';
        $filePath = 'sertifikat/' . $fileName;
        
        // Buat direktori jika belum ada
        Storage::disk('public')->makeDirectory('sertifikat');
        
        // Simpan PDF
        $fullPath = storage_path('app/public/' . $filePath);
        $pdf->Output($fullPath, 'F');
        
        // Return data untuk disimpan ke database
        return [
            'file_path' => $filePath,
            'nomor_sertifikat' => $nomorSertifikat
        ];
    }
    
    public function generateSertifikatFromHtml($userId)
    {
        $user = User::find($userId);
        $biodata = Biodata::where('user_id', $userId)->first();
        
        if (!$user || !$biodata) {
            throw new \Exception('Data user atau biodata tidak ditemukan');
        }
        
        // Format tanggal
        $tanggalMulai = \Carbon\Carbon::parse($biodata->tanggal_mulai)->translatedFormat('d F Y');
        $tanggalSelesai = \Carbon\Carbon::parse($biodata->tanggal_selesai)->translatedFormat('d F Y');
        
        // Generate nomor sertifikat
        $nomorSertifikat = $this->generateNomorSertifikat();
        
        // Data untuk template
        $data = [
            'nama' => strtoupper($biodata->nama_lengkap),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'nomor_sertifikat' => $nomorSertifikat,
            // 'asal_sekolah' => $biodata->asal_sekolah,
            // 'jurusan' => $biodata->jurusan,
            'tanggal_sekarang' => \Carbon\Carbon::now()->translatedFormat('d F Y')
        ];
        
        // Generate PDF dari HTML
        $pdf = Pdf::loadView('templates.sertifikat', $data);
        $pdf->setPaper('A4', 'landscape');
        
        // Generate nama file
        $fileName = 'sertifikat_' . str_replace(' ', '_', strtolower($user->name)) . '_' . date('Y-m-d') . '.pdf';
        $filePath = 'sertifikat/' . $fileName;
        
        // Buat direktori jika belum ada
        Storage::disk('public')->makeDirectory('sertifikat');
        
        // Simpan PDF
        $fullPath = storage_path('app/public/' . $filePath);
        $pdf->save($fullPath);
        
        // Return data untuk disimpan ke database
        return [
            'file_path' => $filePath,
            'nomor_sertifikat' => $nomorSertifikat
        ];
    }
    
    public function generateSertifikatAlternative($userId)
    {
        return $this->generateSertifikatFromHtml($userId);
    }

    private function generateNomorSertifikat()
    {
        // Ambil tahun dan bulan saat ini
        $tahun = date('Y');
        $bulan = date('m');
        
        // Hitung jumlah sertifikat yang sudah dibuat di bulan ini
        $count = \App\Models\Sertifikat::whereYear('created_at', $tahun)
                                      ->whereMonth('created_at', $bulan)
                                      ->count();
        
        // Nomor urut dimulai dari 1
        $nomorUrut = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        // Format: nomor/bulan/tahun (contoh: 001/08/2025)
        return $nomorUrut . '/' . $bulan . '/' . $tahun;
    }
    
    private function generateHtmlTemplate($biodata, $tanggalMulai, $tanggalSelesai)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Sertifikat Magang</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; margin: 0; padding: 50px; }
                .certificate { border: 5px solid #0066cc; padding: 50px; margin: 20px; }
                .title { font-size: 36px; font-weight: bold; color: #0066cc; margin-bottom: 30px; }
                .subtitle { font-size: 24px; margin-bottom: 40px; }
                .name { font-size: 28px; font-weight: bold; margin: 30px 0; text-decoration: underline; }
                .details { font-size: 16px; line-height: 1.8; margin: 20px 0; }
                .signature { margin-top: 80px; }
            </style>
        </head>
        <body>
            <div class="certificate">
                <div class="title">SERTIFIKAT</div>
                <div class="subtitle">MAGANG KERJA</div>
                
                <p>Diberikan kepada:</p>
                <div class="name">' . strtoupper($biodata->nama_lengkap) . '</div>
                
                <div class="details">
                    <p>Yang telah melaksanakan kegiatan magang di</p>
                    <p><strong>Dinas Komunikasi dan Informatika Kota Bukittinggi</strong></p>
                    <p>Periode: ' . $tanggalMulai . ' s/d ' . $tanggalSelesai . '</p>
                    
                </div>
                
                <div class="signature">
                    <p>Bukittinggi, ' . \Carbon\Carbon::now()->translatedFormat('d F Y') . '</p>
                    <br><br><br>
                    <p><strong>Kepala Dinas Komunikasi dan Informatika</strong></p>
                    <p><strong>Kota Bukittinggi</strong></p>
                </div>
            </div>
        </body>
        </html>';
    }
}
