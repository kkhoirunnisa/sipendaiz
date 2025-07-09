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
            <p class="text-dark-50 mb-3 small">Lengkapi form di bawah untuk menambahkan pengurus masjid</p>

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
                            <input type="file" name="foto_ttd" id="foto_ttd"
                                class="form-control @error('foto_ttd') is-invalid @enderror" accept="image/*">

                            {{-- Preview gambar dari session temporary --}}
                            @if (session('temp_foto_ttd'))
                            <div class="mt-2" id="temp-preview">
                                <img src="{{ asset('storage/' . session('temp_foto_ttd')) }}" alt="Preview TTD"
                                    style="max-height: 100px;" class="border rounded">
                                <input type="hidden" name="foto_ttd_temp" value="{{ session('temp_foto_ttd') }}">
                                <div class="mt-1">
                                    <small class="text-muted">File yang sudah dipilih sebelumnya</small>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="hapusGambarTemp()">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            @endif

                            <!-- {{-- Preview gambar baru yang dipilih --}} -->
                            <div class="mt-2" id="preview-wrapper" style="display: none;">
                                <img id="preview-gambar" src="#" alt="Preview Gambar" class="img-thumbnail" style="max-height: 93px;">
                                <div class="mt-1">
                                    <small class="text-muted">Gambar baru yang dipilih</small>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="hapusGambarBaru()">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            <div class="form-text">Format PNG, JPG, JPEG. Maksimal 10240</div>
                            @error('foto_ttd')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label fw-bold text-dark">Tanggal Mulai Jabatan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}" required>
                            @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Jika ada pengurus aktif sebelumnya untuk jabatan yang sama, maka akan otomatis dinonaktifkan.
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
            text: "Apakah Anda yakin ingin menyimpan data pengurus masjid ini?",
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

    function hapusGambarTemp() {
        // Hapus preview temporary
        const tempPreview = document.getElementById('temp-preview');
        if (tempPreview) {
            tempPreview.remove();
        }

        // Reset input file
        document.getElementById('foto_ttd').value = '';

        // Hapus preview gambar baru jika ada
        const preview = document.getElementById('preview-gambar');
        preview.classList.add('d-none');

        // Kirim request untuk menghapus file temporary dari server
        fetch('{{ route("pejabat.index") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                action: 'delete_temp_file'
            })
        });
    }
</script>

<!-- Notifikasi Error dan Success -->
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session("error") }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545',
    });
</script>
@endif

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#198754',
    });
</script>
@endif

<!-- Notifikasi Error Validasi -->
@if ($errors->any())
<script>
    let errorMessages = '';

    Swal.fire({
        icon: 'error',
        title: 'Error Validasi!',
        html: errorMessages.replace(/\n/g, '<br>'),
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545',
    });
</script>
@endif

<script>
    // Preview gambar saat dipilih
    document.getElementById("foto_ttd").addEventListener("change", function(event) {
        const input = event.target;
        const preview = document.getElementById("preview-gambar");
        const wrapper = document.getElementById("preview-wrapper");
        const tempPreview = document.getElementById("temp-preview");

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                wrapper.style.display = 'block';
                preview.classList.remove("d-none");

                // Sembunyikan preview temporary jika ada
                if (tempPreview) {
                    tempPreview.style.display = 'none';
                }
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            wrapper.style.display = 'none';
            if (tempPreview) {
                tempPreview.style.display = 'block';
            }
        }
    });

    // Jika ada file temporary, tampilkan preview
    document.addEventListener('DOMContentLoaded', function() {
        const tempPreview = document.getElementById('temp-preview');
        if (tempPreview) {
            tempPreview.style.display = 'block';
        }
    });
</script>
<script>
    function hapusGambarBaru() {
        // Reset input file
        document.getElementById('foto_ttd').value = '';

        // Sembunyikan wrapper preview
        const wrapper = document.getElementById('preview-wrapper');
        if (wrapper) {
            wrapper.style.display = 'none';
        }

        // Tampilkan kembali preview gambar temp jika ada
        const tempPreview = document.getElementById('temp-preview');
        if (tempPreview) {
            tempPreview.style.display = 'block';
        }
    }
</script>
@endsection