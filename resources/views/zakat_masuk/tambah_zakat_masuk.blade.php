@extends('layouts.app')

@section('content')
<div class="" style="padding: 0 5px 0 -5px;">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="bi bi-box2-fill fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Zakat Masuk</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Zakat</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">Pemasukan</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Tambah Zakat Masuk</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Tambah Pemasukan Zakat Baru</h4>
            <p class="text-dark-50 mb-3 small">Lengkapi form di bawah untuk menambahkan data zakat masjid</p>

            <a href="{{ route('zakat_masuk.index') }}" class="btn btn-success mb-3"> <i class="bi bi-arrow-left-circle-fill"></i>
                Kembali
            </a>

            <form id="tambah-form" action="{{ route('zakat_masuk.store') }}" method="POST">
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
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Tambah Zakat Masuk
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- User dan Tanggal -->
                            <div class="col-md-6 mb-3">
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
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label fw-bold text-dark">
                                    <i class="bi bi-calendar2-range-fill me-2 text-primary"></i>
                                    Tanggal<span class="text-danger"> *</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                                @error('tanggal')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- bentuk dan Jenis Zakat -->
                            <div class="col-md-6 mb-3">
                                <label for="jenis_zakat" class="form-label fw-bold text-dark">
                                    <i class="fas fa-boxes-stacked me-2 text-primary"></i>
                                    Jenis Zakat<span class="text-danger"> *</span>
                                </label>
                                <select name="jenis_zakat" id="jenis_zakat" class="form-select" required>
                                    <option value="" disabled {{ old('jenis_zakat') == null ? 'selected' : '' }}>Pilih Jenis Zakat</option>
                                    <option value="Fitrah" {{ old('jenis_zakat') == 'Fitrah' ? 'selected' : '' }}>Fitrah</option>
                                    <option value="Maal" {{ old('jenis_zakat') == 'Maal' ? 'selected' : '' }}>Maal</option>
                                </select>
                                @error('jenis_zakat')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3 ">
                                <label for="bentuk_zakat" class="form-label fw-bold text-dark">
                                    <i class="bi bi-box2-fill me-2 text-primary"></i>
                                    Bentuk Zakat<span class="text-danger"> *</span>
                                </label>
                                <select name="bentuk_zakat" class="form-select @error('bentuk_zakat') is-invalid @enderror" id="bentuk_zakat" required>
                                    <option value="Uang" {{ old('bentuk_zakat') == 'Uang' ? 'selected' : '' }}>Uang</option>
                                    <option value="Beras" {{ old('bentuk_zakat', 'Beras') == 'Beras' ? 'selected' : '' }}>Beras</option>
                                </select>
                                @error('bentuk_zakat')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- jumlah dan Nominal -->
                            <div class="col-md-6 mb-3" id="jumlah_section">
                                <label for="jumlah" class="form-label fw-bold text-dark">
                                    <i class="fas fa-scale-balanced me-2 text-primary"></i>
                                    Jumlah (Kg)<span class="text-danger"> *</span>
                                </label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan berat beras, contoh 2.8" value="{{ old('jumlah') }}" required>
                                @error('jumlah')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3" id="nominal_section">
                                <label for="nominal" class="form-label fw-bold text-dark">
                                    <i class="bi bi-cash-coin me-2 text-primary"></i>
                                    Nominal (Rp)<span class="text-danger"> *</span>
                                </label>
                                <input type="text" name="nominal" id="nominal" class="form-control"
                                    placeholder="Masukkan besar nominal zakat, contoh 45000"
                                    value="{{ old('nominal') }}" required>
                                @error('nominal')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Jumlah dan Keterangan -->
                            <div class="mb-3">
                                <label for="keterangan" class="form-label fw-bold text-dark">
                                    <i class="bi bi-card-text me-2 text-primary"></i>
                                    Keterangan (Muzaki)<span class="text-danger"> *</span>
                                </label>
                                <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan nama muzaki" rows="3" required>{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="validasiSebelumKonfirmasi()">
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
    function validasiSebelumKonfirmasi() {
        const form = document.getElementById('tambah-form');

        // Bersihkan error lama dari validasi dinamis sebelumnya
        document.querySelectorAll('.text-danger.dynamic-error').forEach(e => e.remove());

        const requiredFields = [
            'tanggal',
            'jenis_zakat',
            'bentuk_zakat',
            'keterangan'
        ];

        // Tambahkan validasi khusus sesuai bentuk zakat
        const bentukZakat = document.getElementById('bentuk_zakat').value.toLowerCase();
        if (bentukZakat === 'uang') {
            requiredFields.push('nominal');
        } else if (bentukZakat === 'beras') {
            requiredFields.push('jumlah');
        }

        let firstInvalidField = null;

        for (const fieldId of requiredFields) {
            const field = document.getElementById(fieldId);
            const val = field?.value?.trim();
            const isEmpty = !val;

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
            title: "Simpan data zakat masuk?",
            text: "Apakah Anda yakin ingin menyimpan data zakat masuk ini?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, simpan",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                // Unformat AutoNumeric sebelum submit
                const nominalElement = AutoNumeric.getAutoNumericElement('#nominal');
                if (nominalElement) {
                    nominalElement.unformat();
                }

                form.submit();
            }
        });
    }
</script>

<script>
    // Update progress bar
    // Inisialisasi elemen global
    const fields = [
        document.getElementById('nama_user'),
        document.getElementById('tanggal'),
        document.getElementById('jenis_zakat'),
        document.getElementById('bentuk_zakat'),
        document.getElementById('jumlah'),
        document.getElementById('keterangan'),
        document.getElementById('nominal')
    ];
    const progressBar = document.getElementById('form-progress');
    const progressText = document.getElementById('progress-text');
    const bentukZakat = document.getElementById('bentuk_zakat');
    const nominalSection = document.getElementById('nominal_section');
    const jumlahSection = document.getElementById('jumlah_section');

    // Fungsi untuk update progress bar
    function updateProgress() {
        let filledFields = 0;
        let visibleFields = 0;

        fields.forEach(field => {
            const section = field.closest('.mb-3'); // Ambil pembungkus
            const isVisible = section ? section.offsetParent !== null : true; // apakah terlihat

            if (isVisible) {
                visibleFields++;
                if (field.value && field.value.trim() !== '') {
                    filledFields++;
                }
            }
        });

        const progress = (visibleFields > 0) ? (filledFields / visibleFields) * 100 : 0;
        progressBar.style.width = progress + '%';
        progressText.textContent = Math.round(progress) + '%';
    }

    // Fungsi toggle kolom
    function toggleFields() {
        const value = bentukZakat.value.toLowerCase();

        if (value === 'beras') {
            nominalSection.style.display = 'none';
            jumlahSection.style.display = 'block';
        } else if (value === 'uang') {
            nominalSection.style.display = 'block';
            jumlahSection.style.display = 'none';
        } else {
            nominalSection.style.display = 'block';
            jumlahSection.style.display = 'block';
        }

        // Update progress setiap toggle
        updateProgress();
    }

    // Event listener saat form berubah
    fields.forEach(field => {
        field.addEventListener('input', updateProgress);
        field.addEventListener('change', updateProgress);
    });

    // Saat DOM selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields();
        updateProgress();
        bentukZakat.addEventListener('change', toggleFields);
    });

    //nominal titik

    //     const autoNumeric = new AutoNumeric('#nominal', {
    //     digitGroupSeparator: '.',
    //     decimalCharacter: ',',
    //     decimalPlaces: 0,
    //     unformatOnSubmit: true, 
    // });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi AutoNumeric untuk input nominal
        const nominalInput = new AutoNumeric('#nominal', {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 0,
            modifyValueOnWheel: false,
            unformatOnSubmit: true
        });
    });
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