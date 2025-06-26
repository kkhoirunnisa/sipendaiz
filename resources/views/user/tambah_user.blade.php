@extends('layouts.app')

@section('content')
<div class="" style="padding: 0 5px 0 -5px;">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">User</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Users</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">User</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Tambah User</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Tambah User Baru</h4>
            <p class="text-dark-50 mb-3 small">Lengkapi form di bawah untuk menambahkan data user / petugas masjid</p>

            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('user.index') }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="tambah-form" action="{{ route('user.store') }}" method="POST">
                @csrf

                <!-- progress pengisian form -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="fw-normal text-success">Progress Pengisian</small>
                        <small class="text-success"><span id="progress-text">0%</span></small>
                    </div>
                    <div class="progress rounded-pill shadow-sm" style="height: 6px;">
                        <div id="form-progress" class="progress-bar bg-success bg-gradient rounded-pill"
                            style="width: 0%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
                <!-- form -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Tambah User
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>
                                Nama<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama user"
                                required value="{{ old('nama') }}">
                            @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label fw-bold text-dark">
                                <i class="fas fa-phone me-2 text-primary"></i>
                                Nomor Telepon<span class="text-danger"> *</span>
                            </label>
                            <input type="text"
                                name="nomor_telepon"
                                id="nomor_telepon"
                                class="form-control"
                                placeholder="Masukkan nomor telepon maks 12 angka"
                                inputmode="numeric"
                                pattern="[0-9]+"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value="{{ old('nomor_telepon') }}">
                            @error('nomor_telepon')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label fw-bold text-dark">
                                <i class="fas fa-shield me-2 text-primary"></i>
                                Role<span class="text-danger"> *</span>
                            </label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="Bendahara" {{ old('role') == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                                <option value="Petugas" {{ old('role') == 'Petugas' ? 'selected' : '' }}>Petugas</option>
                                <option value="Ketua" {{ old('role') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                            </select>
                            @error('role')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold text-dark">
                                <i class="fas fa-circle-user me-2 text-primary"></i>
                                Username<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username user"
                                required value="{{ old('username') }}">
                            @error('username')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-dark">
                                <i class="fas fa-lock me-2 text-primary"></i>
                                Password<span class="text-danger"> *</span>
                            </label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password user" required>
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="konfirmasiTambahUser()">
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
    function konfirmasiTambahUser() {
        Swal.fire({
            title: "Simpan User?",
            text: "Apakah Anda yakin ingin menyimpan data user ini?",
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
    // Update progress bar
    // Definisi fungsi
    function updateProgress() {
        let filledFields = 0;
        const totalFields = fields.length;

        fields.forEach(field => {
            const id = field.id;
            const value = field.value.trim();

            if (id === 'nomor_telepon') {
                if (/^\d+$/.test(value) && value.length >= 10) { //minimal  digit
                    filledFields++;
                }
            } else if (value !== '') {
                filledFields++;
            }
        });

        const progress = (filledFields / totalFields) * 100;
        progressBar.style.width = progress + '%';
        progressText.textContent = Math.round(progress) + '%';
    }



    // Inisialisasi elemen & event listener
    const fields = [
        document.getElementById('nama'),
        document.getElementById('nomor_telepon'),
        document.getElementById('role'),
        document.getElementById('username'),
        document.getElementById('password')
    ];
    const progressBar = document.getElementById('form-progress');
    const progressText = document.getElementById('progress-text');

    fields.forEach(field => {
        field.addEventListener('input', updateProgress);
        field.addEventListener('change', updateProgress); // untuk <select>
    });

    document.addEventListener('DOMContentLoaded', updateProgress);
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