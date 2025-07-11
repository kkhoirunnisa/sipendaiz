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
                    <span class="text-success fw-semibold">Edit Zakat Masuk</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Edit Data Zakat Masuk</h4>
            <p class="text-dark-50 mb-3 small">Ubah data pada form di bawah untuk update data zakat masjid</p>
            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('zakat_masuk.index') }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="edit-form" action="{{ route('zakat_masuk.update', $zakatMasuk->id) }}" method="POST">
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
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Edit Zakat Masuk
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <!-- User dan Tanggal -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_user" class="form-label fw-bold text-dark">
                                        <i class="fas fa-user me-2 text-warning"></i>
                                        User
                                    </label>
                                    <input type="text" class="form-control bg-light" id="nama_user" value="{{ $user->nama }}" readonly>
                                    <input type="hidden" name="id_users" value="{{ $user->id }}">
                                    @error('id_user')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal" class="form-label fw-bold text-dark">
                                        <i class="bi bi-calendar2-range-fill me-2 text-warning"></i>
                                        Tanggal<span class="text-danger"> *</span>
                                    </label>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                                        value="{{ old('tanggal', $zakatMasuk->tanggal) }}" required>
                                    @error('tanggal')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- bentuk zakat dan Jenis Zakat -->
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_zakat" class="form-label fw-bold text-dark">
                                        <i class="fas fa-boxes-stacked me-2 text-warning"></i>
                                        Jenis Zakat<span class="text-danger"> *</span>
                                    </label>
                                    <select name="jenis_zakat" id="jenis_zakat" class="form-select" required>
                                        <option value="" disabled>Pilih Jenis Zakat</option>
                                        <option value="Fitrah" {{ old('jenis_zakat', $zakatMasuk->jenis_zakat) == 'Fitrah' ? 'selected' : '' }}>Fitrah</option>
                                        <option value="Maal" {{ old('jenis_zakat', $zakatMasuk->jenis_zakat) == 'Maal' ? 'selected' : '' }}>Maal</option>
                                    </select>
                                    @error('jenis_zakat')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bentuk_zakat" class="form-label fw-bold text-dark">
                                        <i class="bi bi-box2-fill me-2 text-warning"></i>
                                        Bentuk Zakat<span class="text-danger"> *</span>
                                    </label>
                                    <select name="bentuk_zakat" id="bentuk_zakat" class="form-select" required>
                                        <option value="Uang" {{ old('bentuk_zakat', $zakatMasuk->bentuk_zakat) == 'Uang' ? 'selected' : '' }}>Uang</option>
                                        <option value="Beras" {{ old('bentuk_zakat', $zakatMasuk->bentuk_zakat) == 'Beras' ? 'selected' : '' }}>Beras</option>
                                    </select>
                                    <small class="text-muted"> Jika akan ubah bentuk zakat hapus terlebih dahulu nominal / jumlah yang telah diisi </small>
                                    @error('bentuk_zakat')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Jumlah dan Nominal -->

                                <div class="col-md-6 mb-3" id="nominal_section">
                                    <label for="nominal" class="form-label fw-bold text-dark">
                                        <i class="bi bi-cash-coin me-2 text-warning"></i>
                                        Nominal (Rp)<span class="text-danger"> *</span>
                                    </label>
                                    <input type="text" name="nominal" id="nominal" class="form-control"
                                        value="{{ old('nominal', $zakatMasuk->nominal) }}" required>
                                    @error('nominal')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3" id="jumlah_section">
                                    <label for="jumlah" class="form-label fw-bold text-dark">
                                        <i class="fas fa-scale-balanced me-2 text-warning"></i>
                                        Jumlah (Kg)<span class="text-danger"> *</span>
                                    </label>
                                    <input type="number" name="jumlah" id="jumlah" class="form-control"
                                        value="{{ old('jumlah', rtrim(rtrim(number_format($zakatMasuk->jumlah, 2, '.', ''), '0'), '.')) }}" required>
                                    @error('jumlah')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Jumlah dan Keterangan -->
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label fw-bold text-dark">
                                        <i class="bi bi-card-text me-2 text-warning"></i>
                                        Keterangan (Muzaki)<span class="text-danger"> *</span>
                                    </label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3" required>{{ old('keterangan', $zakatMasuk->keterangan) }}</textarea>
                                    @error('keterangan')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-warning" onclick="validasiSebelumKonfirmasi()">
                                    <i class="bi bi-save-fill me-1"></i>
                                    Edit
                                </button>
                            </div>
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

        // Hapus pesan error dinamis sebelumnya
        document.querySelectorAll('.text-danger.dynamic-error').forEach(e => e.remove());

        // Field yang selalu wajib
        const requiredFields = [
            'tanggal',
            'jenis_zakat',
            'bentuk_zakat',
            'keterangan'
        ];

        // Tambah field wajib berdasarkan bentuk zakat
        const bentukZakat = document.getElementById('bentuk_zakat')?.value?.toLowerCase();
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

        // Konfirmasi sebelum submit
        Swal.fire({
            title: "Simpan Perubahan Data?",
            text: "Apakah Anda yakin ingin menyimpan perubahan data zakat masuk ini?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, simpan",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                // Unformat input nominal (jika AutoNumeric aktif)
                const nominalElement = AutoNumeric.getAutoNumericElement('#nominal');
                if (nominalElement) {
                    document.getElementById('nominal').value = nominalElement.getNumber().toString();
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
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nominalInput = new AutoNumeric('#nominal', {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 0,
            modifyValueOnWheel: false,
            unformatOnSubmit: false
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