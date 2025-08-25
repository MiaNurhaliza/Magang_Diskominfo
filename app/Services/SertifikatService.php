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
        \Log::info("SertifikatService: generateSertifikat called for user ID: " . $userId);
        
        // Ambil data user dan biodata
        $user = User::find($userId);
        $biodata = Biodata::where('user_id', $userId)->first();
        
        if (!$user || !$biodata) {
            throw new \Exception('Data user atau biodata tidak ditemukan');
        }
        
        \Log::info("SertifikatService: User and biodata found");
        
        // Path template PDF
        $templatePath = storage_path('app/templates/sertifikat.pdf');
        
        if (file_exists($templatePath)) {
            \Log::info("SertifikatService: Using PDF template from: " . $templatePath);
            return $this->generateSertifikatFromPdfTemplate($userId);
        } else {
            \Log::warning("SertifikatService: PDF template not found, falling back to HTML");
            return $this->generateSertifikatFromHtml($userId);
        }
    }

    public function generateSertifikatFromPdfTemplate($userId)
    {
        \Log::info("SertifikatService: generateSertifikatFromPdfTemplate started");
        
        $user = User::find($userId);
        $biodata = Biodata::where('user_id', $userId)->first();
        
        if (!$user || !$biodata) {
            throw new \Exception('Data user atau biodata tidak ditemukan');
        }
        
        // Path template PDF
        $templatePath = storage_path('app/templates/sertifikat.pdf');
        
        if (!file_exists($templatePath)) {
            \Log::error("SertifikatService: Template PDF not found at: " . $templatePath);
            throw new \Exception('Template PDF tidak ditemukan');
        }
        
        \Log::info("SertifikatService: Template PDF found at: " . $templatePath);
        
        // Generate nomor sertifikat
        $nomorSertifikat = $this->generateNomorSertifikat();
        
        // Format tanggal
        $tanggalMulai = \Carbon\Carbon::parse($biodata->tanggal_mulai)->translatedFormat('d F Y');
        $tanggalSelesai = \Carbon\Carbon::parse($biodata->tanggal_selesai)->translatedFormat('d F Y');
        $tanggalSekarang = \Carbon\Carbon::now()->translatedFormat('d F Y');
        
        \Log::info("SertifikatService: Data prepared - Name: " . $biodata->nama_lengkap . ", Dates: " . $tanggalMulai . " - " . $tanggalSelesai);
        
        try {
            // Cek apakah FPDI tersedia
            if (!class_exists('\setasign\Fpdi\Fpdi')) {
                \Log::warning("SertifikatService: FPDI not available, falling back to HTML");
                return $this->generateSertifikatFromHtml($userId);
            }
            
            // Buat instance FPDI
            $pdf = new \setasign\Fpdi\Fpdi();
            
            // Import halaman dari template
            $pageCount = $pdf->setSourceFile($templatePath);
            \Log::info("SertifikatService: Template has " . $pageCount . " pages");
            
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // Import halaman
                $templateId = $pdf->importPage($pageNo);
                $pdf->AddPage('L', 'A4'); // Landscape orientation
                $pdf->useTemplate($templateId);
                
                // Set font
                $pdf->SetFont('Arial', '', 12);
                $pdf->SetTextColor(0, 0, 0);
                
                // Tambahkan teks ke PDF (koordinat disesuaikan dengan template)
                // Nomor sertifikat (kanan atas)
                $pdf->SetXY(220, 15);
                $pdf->SetFont('Arial', '', 10);
                $pdf->Write(0, 'No: ' . $nomorSertifikat);
                
                // Nama peserta (tengah)
                $pdf->SetFont('Arial', 'B', 18);
                $pdf->SetXY(50, 120);
                $pdf->Cell(200, 10, $biodata->nama_lengkap, 0, 0, 'C');
                
                // Periode magang
                $pdf->SetFont('Arial', '', 12);
                $pdf->SetXY(50, 145);
                $pdf->Cell(200, 10, $tanggalMulai . ' s/d ' . $tanggalSelesai, 0, 0, 'C');
                
                // Tanggal penerbitan (kiri bawah)
                $pdf->SetXY(50, 200);
                $pdf->Write(0, 'Bukittinggi, ' . $tanggalSekarang);
            }
            
            // Generate nama file
            $fileName = 'sertifikat_' . str_replace(' ', '_', strtolower($biodata->nama_lengkap)) . '_' . date('Y-m-d') . '.pdf';
            $filePath = 'sertifikat/' . $fileName;
            
            // Buat direktori jika belum ada
            Storage::disk('public')->makeDirectory('sertifikat');
            
            // Simpan PDF
            $fullPath = storage_path('app/public/' . $filePath);
            \Log::info("SertifikatService: Saving PDF to: " . $fullPath);
            
            $pdf->Output('F', $fullPath);
            
            \Log::info("SertifikatService: PDF saved successfully using template");
            
            // Return data untuk disimpan ke database
            return [
                'file_path' => $filePath,
                'nomor_sertifikat' => $nomorSertifikat
            ];
            
        } catch (\Exception $e) {
            \Log::error("SertifikatService: PDF template generation failed: " . $e->getMessage());
            \Log::error("SertifikatService: Falling back to HTML template");
            // Fallback ke HTML jika PDF template gagal
            return $this->generateSertifikatFromHtml($userId);
        }
    }
    
    public function generateSertifikatFromHtml($userId)
    {
        \Log::info("SertifikatService: generateSertifikatFromHtml started");
        
        $user = User::find($userId);
        $biodata = Biodata::where('user_id', $userId)->first();
        
        if (!$user || !$biodata) {
            throw new \Exception('Data user atau biodata tidak ditemukan');
        }
        
        \Log::info("SertifikatService: Generating certificate number");
        
        // Generate nomor sertifikat
        $nomorSertifikat = $this->generateNomorSertifikat();
        
        \Log::info("SertifikatService: Certificate number generated: " . $nomorSertifikat);
        
        // Format tanggal
        $tanggalMulai = \Carbon\Carbon::parse($biodata->tanggal_mulai)->translatedFormat('d F Y');
        $tanggalSelesai = \Carbon\Carbon::parse($biodata->tanggal_selesai)->translatedFormat('d F Y');
        $tanggalSekarang = \Carbon\Carbon::now()->translatedFormat('d F Y');
        
        // Data untuk template
        $data = [
            'nama' => $biodata->nama_lengkap,
            'nomor_sertifikat' => $nomorSertifikat,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'tanggal_sekarang' => $tanggalSekarang
        ];
        
        \Log::info("SertifikatService: Template data prepared", $data);
        
        try {
            // Generate PDF dari HTML
            \Log::info("SertifikatService: Loading PDF view");
            $pdf = Pdf::loadView('templates.sertifikat', $data);
            $pdf->setPaper('A4', 'landscape');
            
            \Log::info("SertifikatService: PDF view loaded successfully");
            
            // Generate nama file
            $fileName = 'sertifikat_' . str_replace(' ', '_', strtolower($biodata->nama_lengkap)) . '_' . date('Y-m-d') . '.pdf';
            $filePath = 'sertifikat/' . $fileName;
            
            \Log::info("SertifikatService: File path: " . $filePath);
            
            // Buat direktori jika belum ada
            Storage::disk('public')->makeDirectory('sertifikat');
            
            // Simpan PDF
            $fullPath = storage_path('app/public/' . $filePath);
            \Log::info("SertifikatService: Saving PDF to: " . $fullPath);
            
            $pdf->save($fullPath);
            
            \Log::info("SertifikatService: PDF saved successfully");
            
            // Return data untuk disimpan ke database
            return [
                'file_path' => $filePath,
                'nomor_sertifikat' => $nomorSertifikat
            ];
            
        } catch (\Exception $e) {
            \Log::error("SertifikatService: PDF generation failed: " . $e->getMessage());
            \Log::error("SertifikatService: Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
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
