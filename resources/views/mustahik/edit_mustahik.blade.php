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
                    <span class="text-success fw-semibold">Edit Mustahik</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Edit Data Mustahik</h4>
            <p class="text-dark-50 mb-3 small">Ubah data pada form di bawah untuk update data penerima zakat</p>

            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('mustahik.index') }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="edit-form" action="{{ route('mustahik.update', $mustahik->id) }}" method="POST">
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
                <!-- progress pengisian form -->
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-warning text-dark fw-bold">
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Edit Mustahik
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-warning"></i>
                                Nama<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="nama" id="nama" value="{{ $mustahik->nama }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label fw-bold text-dark">
                                <i class="fas fa-tags me-2 text-warning"></i>
                                Kategori Penerima<span class="text-danger"> *</span>
                            </label>
                            <select name="kategori" id="kategori" class="form-select" required>
                                <option value="" disabled {{ old('kategori', $mustahik->kategori) == '' ? 'selected' : '' }}>Pilih Kategori</option>
                                <option value="Fakir" {{ old('kategori', $mustahik->kategori) == 'Fakir' ? 'selected' : '' }}>Fakir</option>
                                <option value="Miskin" {{ old('kategori', $mustahik->kategori) == 'Miskin' ? 'selected' : '' }}>Miskin</option>
                                <option value="Amil" {{ old('kategori', $mustahik->kategori) == 'Amil' ? 'selected' : '' }}>Amil</option>
                                <option value="Mualaf" {{ old('kategori', $mustahik->kategori) == 'Mualaf' ? 'selected' : '' }}>Mualaf</option>
                                <option value="Riqab/Budak" {{ old('kategori', $mustahik->kategori) == 'Riqab/Budak' ? 'selected' : '' }}>Riqab / Budak</option>
                                <option value="Gharimin/Orang Berutang" {{ old('kategori', $mustahik->kategori) == 'Gharimin/Orang Berutang' ? 'selected' : '' }}>Gharimin / Orang Berutang</option>
                                <option value="Fi Sabilillah" {{ old('kategori', $mustahik->kategori) == 'Fi Sabilillah' ? 'selected' : '' }}>Fi Sabilillah</option>
                                <option value="Ibnu Sabil/Musafir" {{ old('kategori', $mustahik->kategori) == 'Ibnu Sabil/Musafir' ? 'selected' : '' }}>Ibnu Sabil / Musafir</option>
                                <option value="Lainnya" {{ old('kategori', $mustahik->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-bold text-dark">
                                <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                                Alamat Penerima<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="alamat" id="alamat" value="{{ $mustahik->alamat }}" class="form-control" required>
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
            html: "Apakah Anda yakin ingin mengedit perubahan mustahik ini?",
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