@extends('layouts.peserta')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Content --}}
        <div class="col-md-10 p-4">
            <h2>Selamat Datang, {{ Auth::user()->name }}</h2>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5>Logbook Harian</h5>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logbookModal">
                            <i class="bi bi-plus"></i> Logbook
                        </button>
                    </div>

                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Langkah Kerja</th>
                                <th>Hasil Akhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logbooks as $i => $logbook)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($logbook->tanggal)->translatedFormat('l, d F Y') }}</td>
                                <td>{{ $logbook->kegiatan }}</td>
                                <td>{{ $logbook->langkah_kerja }}</td>
                                <td>
                                    @if($logbook->hasil_akhir)
                                        @php
                                            $extension = pathinfo($logbook->hasil_akhir, PATHINFO_EXTENSION);
                                        @endphp
                                        @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                            <img src="{{ asset('storage/' . $logbook->hasil_akhir) }}" 
                                                 alt="Hasil Akhir" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 100px; max-height: 60px; cursor: pointer;"
                                                 onclick="showImageModal('{{ asset('storage/' . $logbook->hasil_akhir) }}')">
                                        @else
                                            <a href="{{ asset('storage/' . $logbook->hasil_akhir) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark"></i> Lihat File
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" 
                                            onclick="editLogbook({{ $logbook->id }})"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editLogbookModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('logbook.destroy', $logbook->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin hapus logbook ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data logbook</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Logbook --}}
<div class="modal fade" id="logbookModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('logbook.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Logbook Harian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Hari, Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="kegiatan" class="form-label">Kegiatan</label>
                        <textarea class="form-control" id="kegiatan" name="kegiatan" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="langkah_kerja" class="form-label">Langkah Kerja</label>
                        <textarea class="form-control" id="langkah_kerja" name="langkah_kerja" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hasil_akhir" class="form-label">Hasil Akhir</label>
                        <input type="file" class="form-control" id="hasil_akhir" name="hasil_akhir" accept=".jpg,.jpeg,.png,.pdf">
                        <small class="text-muted">Format: JPG, JPEG, PNG, PDF (Max: 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Logbook --}}
<div class="modal fade" id="editLogbookModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editLogbookForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Logbook Harian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_tanggal" class="form-label">Hari, Tanggal</label>
                        <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_kegiatan" class="form-label">Kegiatan</label>
                        <textarea class="form-control" id="edit_kegiatan" name="kegiatan" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_langkah_kerja" class="form-label">Langkah Kerja</label>
                        <textarea class="form-control" id="edit_langkah_kerja" name="langkah_kerja" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_hasil_akhir" class="form-label">Hasil Akhir</label>
                        <input type="file" class="form-control" id="edit_hasil_akhir" name="hasil_akhir" accept=".jpg,.jpeg,.png,.pdf">
                        <small class="text-muted">Format: JPG, JPEG, PNG, PDF (Max: 2MB). Kosongkan jika tidak ingin mengubah file.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Preview Image --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Hasil Akhir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="Preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
// Set tanggal hari ini sebagai default
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal').value = today;
});

// Function untuk edit logbook
function editLogbook(id) {
    fetch(`/logbook/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_tanggal').value = data.tanggal;
            document.getElementById('edit_kegiatan').value = data.kegiatan;
            document.getElementById('edit_langkah_kerja').value = data.langkah_kerja;
            document.getElementById('editLogbookForm').action = `/logbook/${id}`;
        })
        .catch(error => console.error('Error:', error));
}

// Function untuk show image modal
function showImageModal(imageSrc) {
    document.getElementById('previewImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

// Reset form saat modal ditutup
document.getElementById('logbookModal').addEventListener('hidden.bs.modal', function () {
    this.querySelector('form').reset();
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal').value = today;
});

document.getElementById('editLogbookModal').addEventListener('hidden.bs.modal', function () {
    this.querySelector('form').reset();
});
</script>

<style>
.img-thumbnail {
    transition: transform 0.2s;
}

.img-thumbnail:hover {
    transform: scale(1.1);
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}
</style>
@endsection
