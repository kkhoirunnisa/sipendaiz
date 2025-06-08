@extends('layouts.app')

@section('content')
<div id="app" data-success="{{ session('success') }}"></div>
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
                <h3 class="mb-0 fw-bold text-dark">Profil {{ auth()->user()->nama }}</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Users</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Profil</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container ">
        <div class="row">
            <!-- Kartu Profil Kiri -->
            <div class="col-md-4 mb-4">
                <div class="card shadow text-center p-4 border-0">
                    <!-- Avatar -->
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow" style="width: 110px; height: 110px;">
                        <i class="bi bi-person-fill text-white" style="font-size: 55px;"></i>
                    </div>
                    <h5 class="fw-bold"> {{ auth()->user()->nama }}</h5>
                    <p class="text-muted mb-0"> {{ auth()->user()->role }}</p>
                </div>
            </div>

            <!-- Detail Profil Kanan -->
            <div class="col-md-6 mb-4">
                <div class="card shadow p-4 border-0">
                    <h5 class="fw-bold mb-3 text-success "><i class="bi bi-info-circle me-1"></i>Detail Profil</h5>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-darked">
                            <i class="fas fa-user me-2 text-success"></i>
                            Nama
                        </label>
                        <input type="text" class="form-control text-white bg-success" value="{{ $profil['nama'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="fas fa-shield me-2 text-success"></i>
                            Role
                        </label>
                        <input type="text" class="form-control text-white bg-success" value="{{ ucfirst($profil['role']) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="fas fa-circle-user me-2 text-success"></i>
                            Username
                        </label>
                        <input type="text" class="form-control text-white bg-success" value="{{ $profil['username'] }}" readonly>
                    </div>
                    <!-- <div class="mb-3">
                        <label class="form-label fw-bold text-dark">
                            <i class="fas fa-lock me-2 text-success"></i>
                            Password
                        </label>
                        <div class="input-group">
                            <input id="passwordInput" type="password" class="form-control text-white bg-success" value="{{ $profil['password'] }}" readonly>
                            <span class="input-group-text text-white bg-success" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash" id="iconToggle"></i>
                            </span>
                        </div>
                    </div> -->
                    <div class="mb-0">
                        <label class="form-label fw-bold text-dark">
                            <i class="fas fa-phone me-2 text-success"></i>
                            Nomor Telepon
                        </label>
                        <input type="text" class="form-control text-white bg-success" value="{{ $profil['nomor_telepon'] }}" readonly>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('profil.edit') }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('passwordInput');
        const icon = document.getElementById('iconToggle');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    });
</script> -->

<!-- Notifikasi Sukses -->
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#2d7d32',
        timer: 4000,
        timerProgressBar: true,
    });
</script>
@endif
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