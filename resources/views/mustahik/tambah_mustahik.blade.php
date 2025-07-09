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
                            <!-- Semua karakter selain huruf (a-z, A-Z) dan spasi akan dihapus (''). -->
                            <input oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama mustahik" value="{{ old('nama') }}" required>
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
                                 <option value="" disabled {{ old('kategori') == null ? 'selected' : '' }}>Pilih Kategori</option>
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
                            <select name="alamat" id="alamat" class="form-select" required>
                                <option value="" disabled {{ old('alamat') == null ? 'selected' : '' }}>Pilih Alamat</option>
                                <option value="Jl. Melati Selatan s/d Wates Timur Utara - DK1" {{ old('alamat') == 'Jl. Melati Selatan s/d Wates Timur Utara - DK1' ? 'selected' : '' }}>Jl. Melati Selatan s/d Wates Timur Utara - DK1</option>
                                <option value="Jl. Nusa Indah Selatan s/d Melati utara - DK2" {{ old('alamat') == 'Jl. Nusa Indah Selatan s/d Melati utara - DK2' ? 'selected' : '' }}>Jl. Nusa Indah Selatan s/d Melati utara - DK2</option>
                                <option value="JL. Nusa indah Utara s/d Kantil Selatan - DK3" {{ old('alamat') == 'JL. Nusa indah Utara s/d Kantil Selatan - DK3' ? 'selected' : '' }}>JL. Nusa indah Utara s/d Kantil Selatan - DK3</option>
                                <option value="JL. Kantil Utara s/d Kenanga - DK4" {{ old('alamat') == 'JL. Kantil Utara s/d Kenanga - DK4' ? 'selected' : '' }}>JL. Kantil Utara s/d Kenanga - DK4</option>
                                <option value="JL. Anggrek, Cempaka - DK5" {{ old('alamat') == 'JL. Anggrek, Cempaka - DK5' ? 'selected' : '' }}>JL. Anggrek, Cempaka - DK5</option>
                                <option value="JL. Asem Utara s/d Cerme - DK6" {{ old('alamat') == 'JL. Asem Utara s/d Cerme - DK6' ? 'selected' : '' }}>JL. Asem Utara s/d Cerme - DK6</option>
                                <option value="JL. Jambu Utara dan Asem Selatan - DK7" {{ old('alamat') == 'JL. Jambu Utara dan Asem Selatan - DK7' ? 'selected' : '' }}>JL. Jambu Utara dan Asem Selatan - DK7</option>
                                <option value="Jl. Jambu Selatan s/d Mangga Utara - DK8" {{ old('alamat') == 'Jl. Jambu Selatan s/d Mangga Utara - DK8' ? 'selected' : '' }}>Jl. Jambu Selatan s/d Mangga Utara - DK8</option>
                                <option value="Jl. Mangga Selatan s/d Sawo Utara - DK9" {{ old('alamat') == 'Jl. Mangga Selatan s/d Sawo Utara - DK9' ? 'selected' : '' }}>Jl. Mangga Selatan s/d Sawo Utara - DK9</option>
                                <option value="Jl. Sawo Selatan s/d Wates Barat Utara - DK10" {{ old('alamat') == 'Jl. Sawo Selatan s/d Wates Barat Utara - DK10' ? 'selected' : '' }}>Jl. Sawo Selatan s/d Wates Barat Utara - DK10</option>
                                <option value="DK Khusus" {{ old('alamat') == 'DK Khusus' ? 'selected' : '' }}>DK Khusus</option>
                            </select>
                            @error('alamat')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="validasiSebelumKonfirmasi()">
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
    function validasiSebelumKonfirmasi() {
        const form = document.getElementById('tambah-form');

        // Bersihkan error lama
        document.querySelectorAll('.text-danger.dynamic-error').forEach(e => e.remove());

        const requiredFields = ['nama', 'kategori', 'alamat'];
        let firstInvalidField = null;

        for (const fieldId of requiredFields) {
            const field = document.getElementById(fieldId);
            let isEmpty = false;

            const val = field?.value?.trim();
            isEmpty = !val;

            if (isEmpty) {
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'dynamic-error', 'small');
                errorDiv.textContent = 'Kolom ini wajib diisi';
                field.parentNode.appendChild(errorDiv);

                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
            }
        }

        if (firstInvalidField) {
            firstInvalidField.focus();
            firstInvalidField.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            Swal.fire({
                icon: 'warning',
                title: 'Lengkapi Data!',
                text: 'Mohon lengkapi semua field yang wajib diisi.',
                confirmButtonColor: '#dc3545',
            });

            return;
        }

        // Semua valid, tampilkan konfirmasi
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
</script>

<script>
    // update progress bar
    // definisi fungsi untuk memperbarui progress bar
    function updateProgress() {
        let filledFields = 0; // hitung jumlah field yang terisi
        const totalFields = fields.length; // total jumlah field dalam form

        // looping untuk mengecek apakah field terisi
        fields.forEach(field => {
            if (field.value && field.value.trim() !== '') {
                filledFields++; // tambah jika field tidak kosong (bukan spasi saja)
            }
        });

        // hitung persentase progress dari field yang terisi
        const progress = (filledFields / totalFields) * 100;

        // atur lebar progress bar sesuai dengan persentase
        progressBar.style.width = progress + '%';

        // tampilkan persentase progress dalam bentuk angka bulat
        progressText.textContent = Math.round(progress) + '%';
    }

    // inisialisasi elemen form dan elemen progress bar
    const fields = [
        document.getElementById('nama'),
        document.getElementById('kategori'),
        document.getElementById('alamat')
    ];

    const progressBar = document.getElementById('form-progress'); // elemen batang progress bar
    const progressText = document.getElementById('progress-text'); // elemen teks persentase progress

    // Ttmbahkan event listener pada setiap field untuk memicu update progress saat input berubah
    fields.forEach(field => {
        field.addEventListener('input', updateProgress); // saat user mengetik
        field.addEventListener('change', updateProgress); // untuk elemen <select> atau perubahan lainnya
    });

    // jalankan updateProgress saat halaman selesai dimuat (untuk menghitung field yg sudah terisi sebelumnya)
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