@extends('layouts.app')

@section('content')
<div class="px-3">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="fas fa-user-plus fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Pengurus</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Users</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">Pengurus</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Tambah Pengurus</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Tambah Pengurus Baru</h4>
            <p class="text-dark-50 mb-3 small">Lengkapi form di bawah untuk menambahkan pejabat masjid</p>

            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('pejabat.index') }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="tambah-form" action="{{ route('pejabat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Tambah Pengurus
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="id_user" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>
                                User
                            </label>
                            <input type="text" class="form-control bg-light" id="nama_user" value="{{ $user->nama }}" readonly>
                            <input type="hidden" name="id_users" value="{{ $user->id }}">
                            @error('id_users')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jabatan" class="form-label fw-bold text-dark">Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan" id="jabatan" class="form-select @error('jabatan') is-invalid @enderror" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach([
                                'ketua_takmir' => 'Ketua Takmir',
                                'bendahara_takmir' => 'Bendahara Takmir',
                                'ketua_pembangunan' => 'Ketua Pembangunan',
                                'bendahara_pembangunan' => 'Bendahara Pembangunan'
                                ] as $value => $label)
                                <option value="{{ $value }}" {{ old('jabatan', request('jabatan')) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold text-dark">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="foto_ttd" class="form-label fw-bold text-dark">Tanda Tangan (Opsional)</label>
                            <input type="file" name="foto_ttd" id="foto_ttd" class="form-control @error('foto_ttd') is-invalid @enderror" accept="image/*">
                            <div class="form-text">Format PNG, JPG, JPEG. Maksimal 2MB</div>
                            @error('foto_ttd')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label fw-bold text-dark">Tanggal Mulai Jabatan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required>
                            @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Jika ada pejabat aktif sebelumnya untuk jabatan yang sama, maka akan otomatis dinonaktifkan.
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="konfirmasiTambah()">
                                <i class="bi bi-save-fill me-1"></i>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function konfirmasiTambah() {
        Swal.fire({
            title: "Simpan Pengurus Masjid?",
            text: "Apakah Anda yakin ingin menyimpan data pejabat masjid ini?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, simpan",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("tambah-form").submit();
            }
        });
    }
</script>
<!-- Notifikasi Error -->
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session("error") }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#2d7d32',
    });
</script>
@endif
@endsection