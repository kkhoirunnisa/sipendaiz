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
                                <i class="bi bi-person-fill me-2 text-warning"></i>Nama<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="nama" class="form-control bg-light" id="nama" value="{{ $profil->nama }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-person-badge-fill me-2 text-warning"></i>Username<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="username" class="form-control bg-light" id="username" value="{{ $profil->username }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-lock-fill me-2 text-warning"></i>Password Lama
                            </label>
                            <input type="password" name="current_password" class="form-control bg-light"
                                id="current_password" placeholder="Masukkan password lama">
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
                            <input type="password" name="new_password" class="form-control bg-light"
                                id="new_password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <small class="text-muted">Kosongkan jika tidak ingin ubah password</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-shield-lock me-2 text-warning"></i>Konfirmasi Password Baru
                            </label>
                            <input type="password" name="new_password_confirmation" class="form-control bg-light"
                                id="new_password_confirmation" placeholder="Ulangi password baru">
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
                            </label><span class="text-danger"> *</span>
                            <input type="text" name="nomor_telepon" class="form-control bg-light"
                                id="nomor_telepon"
                                inputmode="numeric"
                                pattern="[0-9]+"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value="{{ $profil->nomor_telepon }}" required>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-warning" onclick="validasiSebelumKonfirmasi()">
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
    function validasiSebelumKonfirmasi() {
        const form = document.getElementById('edit-form');
        document.querySelectorAll('.text-danger.dynamic-error').forEach(e => e.remove());

        const fieldIds = ['nama', 'username', 'nomor_telepon'];
        let isValid = true;
        let firstInvalidField = null;

        for (const fieldId of fieldIds) {
            const field = document.getElementById(fieldId);
            const val = field?.value?.trim() || '';
            let errorMessage = '';

            if (val === '') {
                errorMessage = 'Kolom ini wajib diisi';
            } else if (fieldId === 'nomor_telepon') {
                if (!/^\d{10,14}$/.test(val)) {
                    errorMessage = 'Nomor telepon harus berupa 10-14 digit angka';
                }
            }

            if (errorMessage !== '') {
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'dynamic-error', 'small');
                errorDiv.textContent = errorMessage;
                field.parentNode.appendChild(errorDiv);

                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
                isValid = false;
            }
        }

        // Validasi password baru (jika ingin ubah password)
        const newPassword = document.getElementById('new_password').value.trim();
        const confirmPassword = document.getElementById('new_password_confirmation').value.trim();

        if (newPassword !== '') {
            if (newPassword.length < 6) {
                const field = document.getElementById('new_password');
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'dynamic-error', 'small');
                errorDiv.textContent = 'Password minimal 6 karakter';
                field.parentNode.appendChild(errorDiv);
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
            }

            if (confirmPassword === '') {
                const field = document.getElementById('new_password_confirmation');
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'dynamic-error', 'small');
                errorDiv.textContent = 'Mohon konfirmasi password baru';
                field.parentNode.appendChild(errorDiv);
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
            } else if (newPassword !== confirmPassword) {
                const field = document.getElementById('new_password_confirmation');
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'dynamic-error', 'small');
                errorDiv.textContent = 'Konfirmasi password tidak cocok';
                field.parentNode.appendChild(errorDiv);
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
            }
        }

        if (!isValid) {
            if (firstInvalidField) {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            Swal.fire({
                icon: 'warning',
                title: 'Lengkapi Data!',
                text: 'Mohon lengkapi data yang wajib diisi atau perbaiki input yang tidak valid.',
                confirmButtonColor: '#dc3545',
            });

            return;
        }

        Swal.fire({
            title: "Simpan Perubahan Data?",
            html: "Apakah Anda yakin ingin menyimpan perubahan data profil ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, edit",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
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