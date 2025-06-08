@extends('layouts.app')

@section('content')
<div class="px-3">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="bi bi-person-circle fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Edit Profil</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Users</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Edit Profil</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Formulir Edit Profil</h4>
            <p class="text-dark-50 mb-3 small">Silakan perbarui data profil Anda melalui formulir di bawah ini</p>

            <a href="{{ route('profil.index') }}" class="btn btn-success mb-3">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="edit-form" action="{{ route('profil.update') }}" method="POST">
                @csrf
                @method('PUT')
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
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-warning text-dark fw-bold">
                        <i class="bi bi-person-lines-fill me-2"></i>Formulir Edit Profil
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-person-fill me-2 text-warning"></i>Nama
                            </label>
                            <input type="text" name="nama" class="form-control bg-light" id="nama" value="{{ $profil->nama }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-person-badge-fill me-2 text-warning"></i>Username
                            </label>
                            <input type="text" name="username" class="form-control bg-light" id="username" value="{{ $profil->username }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-lock-fill me-2 text-warning"></i>Password Lama
                            </label>
                            <input type="password" name="current_password" class="form-control bg-light" id="current_password" placeholder="Masukkan password lama">
                            <small class="text-muted">Kosongkan jika tidak ingin ubah password</small>
                            <div>
                                @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-shield-lock-fill me-2 text-warning"></i>Password Baru
                            </label>
                            <input type="password" name="new_password" class="form-control bg-light" id="new_password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <small class="text-muted">Kosongkan jika tidak ingin ubah password</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-shield-lock me-2 text-warning"></i>Konfirmasi Password Baru
                            </label>
                            <input type="password" name="new_password_confirmation" class="form-control bg-light" id="new_password_confirmation" placeholder="Ulangi password baru">
                            <small class="text-muted">Kosongkan jika tidak ingin ubah password</small>
                            <div>
                                @error('new_password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-telephone-fill me-2 text-warning"></i>Nomor Telepon
                            </label>
                            <input type="text" name="nomor_telepon" class="form-control bg-light"
                                id="nomor_telepon"
                                inputmode="numeric"
                                pattern="[0-9]+"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value="{{ $profil->nomor_telepon }}">
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-warning" onclick="konfirmasiEdit()">
                                <i class="bi bi-save-fill me-1"></i>Edit
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function konfirmasiEdit() {
        Swal.fire({
            title: "Edit Perubahan?",
            html: "Apakah Anda yakin ingin mengedit perubahan profil ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, edit",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("edit-form").submit();
            }
        });
    }
</script>
<script>
    // Update progress bar
    // Definisi fungsi
    function updateProgress() {
        let filledFields = 0;
        const totalFields = fields.length;

        fields.forEach(field => {
            const id = field.id;
            const value = field.value.trim();

            if (id === 'nomor_telepon') {
                if (/^\d+$/.test(value) && value.length >= 10) { //minimal 10 digit
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
        document.getElementById('username'),
        document.getElementById('current_password'),
        document.getElementById('new_password'),
        document.getElementById('new_password_confirmation'),
        document.getElementById('nomor_telepon')
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
@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ $errors->first() }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#2d7d32',
    });
</script>
@endif

@endsection