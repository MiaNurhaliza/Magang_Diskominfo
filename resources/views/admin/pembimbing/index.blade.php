@extends('layouts.backend')

@section('title', 'Manajemen Pembimbing')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title mb-0">Manajemen Pembimbing</h4>
                <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="mdi mdi-plus"></i> Tambah Pembimbing
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Add spacing before table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0" id="pembimbingTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Jumlah Mahasiswa</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembimbings as $index => $pembimbing)
                                <tr>
                                    <td>{{ $pembimbings->firstItem() + $index }}</td>
                                    <td>{{ $pembimbing->nama }}</td>
                                    <td>{{ $pembimbing->email }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $pembimbing->mahasiswas_count ?? 0 }} Mahasiswa</span>
                                    </td>
                                    <td>
                                        @if($pembimbing->status === 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $pembimbing->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#assignModal{{ $pembimbing->id }}">
                                            <i class="bi bi-person-plus"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.pembimbing.destroy', $pembimbing->id) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus pembimbing ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                               
                                

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $pembimbing->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Pembimbing</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.pembimbing.update', $pembimbing->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama{{ $pembimbing->id }}" class="form-label">Nama <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="nama{{ $pembimbing->id }}" name="nama" value="{{ $pembimbing->nama }}" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="email{{ $pembimbing->id }}" class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="email{{ $pembimbing->id }}" name="email" value="{{ $pembimbing->email }}" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="status{{ $pembimbing->id }}" class="form-label">Status <span class="text-danger">*</span></label>
                                                        <select class="form-select" id="status{{ $pembimbing->id }}" name="status" required>
                                                            <option value="aktif" {{ $pembimbing->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                            <option value="nonaktif" {{ $pembimbing->status === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assign Mahasiswa Modal -->
                                <div class="modal fade" id="assignModal{{ $pembimbing->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Assign Mahasiswa ke {{ $pembimbing->nama }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.pembimbing.assign-mahasiswa', $pembimbing->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Pilih Mahasiswa:</label>
                                                        <div class="row">
                                                            @foreach($mahasiswasAvailable ?? [] as $mahasiswa)
                                                            <div class="col-md-6 mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="mahasiswa_ids[]" value="{{ $mahasiswa->id }}" id="mahasiswa{{ $pembimbing->id }}_{{ $mahasiswa->id }}">
                                                                    <label class="form-check-label" for="mahasiswa{{ $pembimbing->id }}_{{ $mahasiswa->id }}">
                                                                        <strong>{{ $mahasiswa->nama_lengkap }}</strong><br>
                                                                        <small class="text-muted">{{ $mahasiswa->nis_nim }} - {{ $mahasiswa->asal_sekolah }}</small>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        @if(empty($mahasiswasAvailable) || count($mahasiswasAvailable) === 0)
                                                            <p class="text-muted">Tidak ada mahasiswa yang tersedia untuk di-assign.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Assign Mahasiswa</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-account-supervisor-outline mdi-48px"></i>
                                            <p class="mt-2">Belum ada data pembimbing</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pembimbings->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Menampilkan {{ $pembimbings->firstItem() }} sampai {{ $pembimbings->lastItem() }} 
                                dari {{ $pembimbings->total() }} data
                            </p>
                        </div>
                        <div>
                            {{ $pembimbings->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pembimbing Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pembimbing.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#createModal form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted!');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            const formData = new FormData(this);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
        });
    }
});
</script> --}}
@endsection

@push('scripts')
<script>
        function deletePembimbing(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pembimbing ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('admin.pembimbing.index') }}/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }


</script>
@endpush