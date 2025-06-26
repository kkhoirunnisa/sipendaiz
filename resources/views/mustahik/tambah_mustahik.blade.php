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
                <h3 class="mb-0 fw-bold text-dark">Mustahik</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Zakat</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">Mustahik</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Tambah Mustahik</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Tambah Mustahik Baru</h4>
            <p class="text-dark-50 mb-3 small">Lengkapi form di bawah untuk menambahkan data penerima zakat</p>

            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('mustahik.index') }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="tambah-form" action="{{ route('mustahik.store') }}" method="POST">
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
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Tambah Mustahik
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>
                                Nama<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama mustahik" required value="{{ old('nama') }}">
                            @error('nama')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label fw-bold text-dark">
                                <i class="fas fa-tags me-2 text-primary"></i>
                                Kategori Penerima<span class="text-danger"> *</span>
                            </label>
                            <select name="kategori" id="kategori" class="form-select" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Fakir" {{ old('kategori') == 'Fakir' ? 'selected' : '' }}>Fakir</option>
                                <option value="Miskin" {{ old('kategori') == 'Miskin' ? 'selected' : '' }}>Miskin</option>
                                <option value="Amil" {{ old('kategori') == 'Amil' ? 'selected' : '' }}>Amil</option>
                                <option value="Mualaf" {{ old('kategori') == 'Mualaf' ? 'selected' : '' }}>Mualaf</option>
                                <option value="Riqab/Budak" {{ old('kategori') == 'Riqab/Budak' ? 'selected' : '' }}>Riqab / Budak</option>
                                <option value="Gharimin/Orang Berutang" {{ old('kategori') == 'gharimin/orangberutang' ? 'selected' : '' }}>Gharimin / Orang Berutang</option>
                                <option value="Fi Sabilillah" {{ old('kategori') == 'FiSabilillah' ? 'selected' : '' }}>Fi Sabilillah</option>
                                <option value="Ibnu Sabil/Musafir" {{ old('kategori') == 'IbnuSabil/Musafir' ? 'selected' : '' }}>Ibnu Sabil / Musafir</option>
                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-bold text-dark">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                Alamat Penerima<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan alamat mustahik, contoh : Jalan Sawo - DK 1"
                                required value="{{ old('alamat') }}">
                            @error('alamat')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="konfirmasiTambah()">
                                <i class="bi bi-save-fill me-1"></i> Simpan
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
            title: "Simpan data mustahik?",
            text: "Apakah Anda yakin ingin menyimpan data mustahik ini?",
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
            if (field.value && field.value.trim() !== '') {
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
        document.getElementById('kategori'),
        document.getElementById('alamat')
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