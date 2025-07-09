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
                <h3 class="mb-0 fw-bold text-dark">Zakat Keluar</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Zakat</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">Pengeluaran</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Tambah Zakat Keluar</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Tambah Pengeluaran Zakat Baru</h4>
            <p class="text-dark-50 mb-3 small">Lengkapi form di bawah untuk menambahkan data zakat masjid</p>

            <!-- form input zakat keluar -->
            <a href="{{ route('zakat_keluar.index') }}" class="btn btn-success mb-3"> <i class="bi bi-arrow-left-circle-fill"></i>
                Kembali
            </a>

            <form id="tambah-form" action="{{ route('zakat_keluar.store') }}" method="POST">
                @csrf
                <!-- container untuk field nominal[] atau jumlah[] secara dinamis -->
                <div id="field-nominal-jumlah"></div>
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
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Tambah Zakat Keluar
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

                        <!-- mustahik bisa multiple -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>
                                Mustahik
                            </label>
                            <select name="id_mustahik[]" id="id_mustahik" class="form-control select2-mustahik" multiple required>
                                @foreach ($mustahik as $item)
                                <option value="{{ $item->id }}"
                                    {{ in_array($item->id, old('id_mustahik', [])) ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih lebih dari satu mustahik jika perlu</small>
                        </div>


                        <!-- Tombol Tambah Mustahik -->
                        <small class="text-muted mb-2 d-block">
                            Jumlah Mustahik Dipilih: <span id="jumlah-mustahik" class="fw-bold">0</span>
                        </small>

                        <!-- Jenis dan Bentuk Zakat -->
                        <div class="row">
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

                            <!-- Bentuk Zakat -->
                            <div class="col-md-6 mb-3">
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

                        <!-- section perhitungan zakat -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <i class="bi bi-calculator me-2"></i>
                                Perhitungan Zakat per Mustahik
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Input Per Mustahik untuk Nominal -->
                                    <div class="col-md-6 mb-3" id="nominal_per_mustahik_section">
                                        <label for="nominal_per_mustahik" class="form-label fw-bold text-dark">
                                            <i class="bi bi-cash-coin me-2 text-primary"></i>
                                            Nominal per Mustahik (Rp)<span class="text-danger"> *</span>
                                        </label>
                                        <input type="text" name="nominal_per_mustahik" id="nominal_per_mustahik" class="form-control" placeholder="Masukkan nominal per mustahik" value="{{ old('nominal_per_mustahik') }}">
                                        @error('nominal_per_mustahik')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Input Per Mustahik untuk Jumlah -->
                                    <div class="col-md-6 mb-3" id="jumlah_per_mustahik_section" style="display: none;">
                                        <label for="jumlah_per_mustahik" class="form-label fw-bold text-dark">
                                            <i class="fas fa-scale-balanced me-2 text-primary"></i>
                                            Jumlah per Mustahik (Kg)<span class="text-danger"> *</span>
                                        </label>
                                        <input type="number" name="jumlah_per_mustahik" id="jumlah_per_mustahik" class="form-control" placeholder="Masukkan jumlah per mustahik" value="{{ old('jumlah_per_mustahik') }}" step="0.01" min="0">
                                        @error('jumlah_per_mustahik')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- hasil perhitungan dan saldo -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-light border">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Perhitungan:</strong><br>
                                                    <span id="perhitungan-text">0 mustahik × Rp 0 = Rp 0</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Sisa Saldo:</strong><br>
                                                    <span id="sisa-saldo">
                                                        <small class="text-muted d-block">Sisa Fitrah Uang: <strong>Rp {{ number_format($sisa['Fitrah_Uang'], 2, ',', '.') }}</strong></small>
                                                        <small class="text-muted d-block">Sisa Maal Uang: <strong>Rp {{ number_format($sisa['Maal_Uang'], 2, ',', '.') }}</strong></small>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields untuk disubmit ke controller (menyimpan nominal / jumlah per mustahik)-->
                        <!-- jika ada 3 mustahik maka
                         <input type="hidden" name="nominal[]" value="20000">
                        <input type="hidden" name="nominal[]" value="20000">
                        <input type="hidden" name="nominal[]" value="20000"> -->
                        <input type="hidden" name="nominal[]" id="nominal_total" value="{{ old('nominal')[0] ?? '' }}">
                        <input type="hidden" name="jumlah[]" id="jumlah_total" value="{{ old('jumlah')[0] ?? '' }}">

                        <!-- Keterangan -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="keterangan" class="form-label fw-bold text-dark">
                                    <i class="bi bi-card-text me-2 text-primary"></i>
                                    Keterangan<span class="text-danger"> *</span>
                                </label>
                                <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan pengeluaran zakat untuk apa" rows="3" required>{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- button simpan -->
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

        // Bersihkan error lama
        document.querySelectorAll('.text-danger.dynamic-error').forEach(e => e.remove());

        let isValid = true;
        let firstInvalidField = null;

        const requiredFields = ['tanggal', 'id_mustahik', 'jenis_zakat', 'bentuk_zakat', 'keterangan'];
        requiredFields.forEach(id => {
            const field = document.getElementById(id);
            if (field && !field.value.trim()) {
                isValid = false;
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'dynamic-error', 'small');
                errorDiv.textContent = 'Kolom ini wajib diisi';
                field.parentNode.appendChild(errorDiv);
                if (!firstInvalidField) firstInvalidField = field;
            }
        });

        // Validasi mustahik
        // const jumlahMustahik = parseInt(document.getElementById('jumlah-mustahik').textContent) || 0;
        // if (jumlahMustahik === 0) {
        //     isValid = false;
        //     Swal.fire({
        //         icon: 'warning',
        //         title: 'Peringatan!',
        //         text: 'Silakan pilih minimal 1 mustahik terlebih dahulu.',
        //         confirmButtonColor: '#0d6efd'
        //     });
        //     return;
        // }

        // Validasi per mustahik (nominal atau jumlah)
        const bentukZakat = document.getElementById('bentuk_zakat').value;
        let inputPerMustahik = 0;

        if (bentukZakat === 'Uang') {
            inputPerMustahik = AutoNumeric.getNumber('#nominal_per_mustahik') || 0;
            if (inputPerMustahik === 0) {
                isValid = false;
                const field = document.getElementById('nominal_per_mustahik');
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'dynamic-error', 'small');
                errorDiv.textContent = 'Nominal per mustahik harus diisi';
                field.parentNode.appendChild(errorDiv);
                if (!firstInvalidField) firstInvalidField = field;
            }
        } else if (bentukZakat === 'Beras') {
            inputPerMustahik = parseFloat(document.getElementById('jumlah_per_mustahik').value) || 0;
            if (inputPerMustahik === 0) {
                isValid = false;
                const field = document.getElementById('jumlah_per_mustahik');
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'dynamic-error', 'small');
                errorDiv.textContent = 'Jumlah per mustahik harus diisi';
                field.parentNode.appendChild(errorDiv);
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
                text: 'Mohon lengkapi semua field yang wajib diisi.',
                confirmButtonColor: '#dc3545',
            });
            return;
        }

        // Semua valid, tampilkan konfirmasi
        Swal.fire({
            title: "Simpan data zakat keluar?",
            html: `<p>Apakah Anda yakin ingin menyimpan data zakat keluar ini?</p>`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, simpan",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                // Unformat nilai nominal uang sebelum submit
                const nominalAutoNumeric = AutoNumeric.getAutoNumericElement('#nominal_per_mustahik');
                if (nominalAutoNumeric) {
                    nominalAutoNumeric.unformat();
                }

                form.submit();
            }
        });
    }
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

<script>
    $(document).ready(function() {
        // inisialisasi select2 untuk elemen mustahik
        function refreshSelect2() {
            $('.select2-mustahik').select2({
                placeholder: "Pilih Mustahik",
                width: '100%',
                closeOnSelect: false,
                allowClear: true
            });
        }

        refreshSelect2();

        // fungsi untuk menghitung jumlah mustahik dan memperbarui perhitungan
        function hitungJumlahMustahik() {
            const select = document.querySelector('select[name="id_mustahik[]"]');
            const selectedOptions = Array.from(select.selectedOptions);
            const jumlahMustahik = selectedOptions.length;

            document.getElementById('jumlah-mustahik').textContent = jumlahMustahik;

            // Selalu panggil hitungTotal setelah menghitung jumlah mustahik
            hitungTotal();

            return jumlahMustahik;
        }

        // fungsi untuk menghitung total zakat keluar berdasarkan jumlah mustahik dan input per mustahik
        function hitungTotal() {
            const jumlahMustahik = parseInt(document.getElementById('jumlah-mustahik').textContent) || 0;
            const bentukZakat = document.getElementById('bentuk_zakat').value;
            const fieldContainer = document.getElementById('field-nominal-jumlah');

            // Kosongkan container sebelum menambahkan ulang
            fieldContainer.innerHTML = '';

            if (bentukZakat === 'Uang') {
                const nominalPerMustahik = AutoNumeric.getNumber('#nominal_per_mustahik') || 0;
                const totalNominal = nominalPerMustahik * jumlahMustahik;

                // Buat hidden input untuk setiap mustahik
                for (let i = 0; i < jumlahMustahik; i++) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'nominal[]';
                    input.value = nominalPerMustahik;
                    fieldContainer.appendChild(input);
                }

                // Update tampilan perhitungan
                document.getElementById('perhitungan-text').textContent =
                    `${jumlahMustahik} mustahik × Rp ${nominalPerMustahik.toLocaleString('id-ID')} = Rp ${totalNominal.toLocaleString('id-ID')}`;

            } else if (bentukZakat === 'Beras') {
                const jumlahPerMustahik = parseFloat(document.getElementById('jumlah_per_mustahik').value) || 0;
                const totalJumlah = jumlahPerMustahik * jumlahMustahik;

                // Buat hidden input untuk setiap mustahik
                for (let i = 0; i < jumlahMustahik; i++) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'jumlah[]';
                    input.value = jumlahPerMustahik;
                    fieldContainer.appendChild(input);
                }

                // Update tampilan perhitungan
                document.getElementById('perhitungan-text').textContent =
                    `${jumlahMustahik} mustahik × ${jumlahPerMustahik} kg = ${totalJumlah} kg`;
            }

            // Update progress setelah perhitungan
            updateProgress();
        }

        // Event listener untuk perubahan select mustahik
        $(document).on('change', 'select[name="id_mustahik[]"]', function() {
            hitungJumlahMustahik();
        });

        // Event listener untuk input per mustahik
        $(document).on('input change', '#nominal_per_mustahik, #jumlah_per_mustahik', function() {
            hitungTotal();
        });

        // Event listener untuk perubahan bentuk zakat
        $(document).on('change', '#bentuk_zakat', function() {
            // Tunggu sebentar untuk memastikan toggleFields() sudah selesai
            setTimeout(function() {
                hitungTotal();
            }, 100);
        });

        // Event listener untuk perubahan jenis zakat
        $(document).on('change', '#jenis_zakat', function() {
            setTimeout(function() {
                hitungTotal();
            }, 100);
        });

        // inisialisasi awal
        hitungJumlahMustahik();
    });

    // Fungsi untuk mengatur field berdasarkan bentuk zakat (diperbaiki)
    function toggleFields() {
        const bentukZakat = document.getElementById('bentuk_zakat');
        const jenisZakat = document.getElementById('jenis_zakat');
        const nominalPerMustahikSection = document.getElementById('nominal_per_mustahik_section');
        const jumlahPerMustahikSection = document.getElementById('jumlah_per_mustahik_section');
        const nominalPerMustahikInput = document.getElementById('nominal_per_mustahik');
        const jumlahPerMustahikInput = document.getElementById('jumlah_per_mustahik');
        const sisaSaldoSpan = document.getElementById('sisa-saldo');

        const bentukValue = bentukZakat.value;

        if (bentukValue === 'Beras') {
            // Sembunyikan nominal per mustahik, tampilkan jumlah per mustahik
            nominalPerMustahikSection.style.display = 'none';
            jumlahPerMustahikSection.style.display = 'block';

            // Hapus required dari nominal, tambah ke jumlah
            nominalPerMustahikInput.removeAttribute('required');
            jumlahPerMustahikInput.setAttribute('required', 'required');

            // Clear nominal value
            nominalPerMustahikInput.value = '';

            // Update sisa saldo display
            sisaSaldoSpan.innerHTML = `
            <small class="text-muted d-block">Sisa Fitrah Beras: <strong>{{ number_format($sisa['Fitrah_Beras'], 2, ',', '.') }} Kg</strong></small>
            <small class="text-muted d-block">Sisa Maal Beras: <strong>{{ number_format($sisa['Maal_Beras'], 2, ',', '.') }} Kg</strong></small>
        `;

        } else if (bentukValue === 'Uang') {
            // Tampilkan nominal per mustahik, sembunyikan jumlah per mustahik
            nominalPerMustahikSection.style.display = 'block';
            jumlahPerMustahikSection.style.display = 'none';

            // Tambah required ke nominal, hapus dari jumlah
            nominalPerMustahikInput.setAttribute('required', 'required');
            jumlahPerMustahikInput.removeAttribute('required');

            // Clear jumlah value
            jumlahPerMustahikInput.value = '';

            // Update sisa saldo display
            sisaSaldoSpan.innerHTML = `
            <small class="text-muted d-block">Sisa Fitrah Uang: <strong>Rp {{ number_format($sisa['Fitrah_Uang'], 2, ',', '.') }}</strong></small>
            <small class="text-muted d-block">Sisa Maal Uang: <strong>Rp {{ number_format($sisa['Maal_Uang'], 2, ',', '.') }}</strong></small>
        `;
        }

        // Update progress dan perhitungan setelah toggle
        updateProgress();

        // Pastikan perhitungan dijalankan setelah field berubah
        setTimeout(function() {
            hitungTotal();
        }, 50);
    }

    // Progress bar functionality (diperbaiki)
    function updateProgress() {
        const fields = [
            document.getElementById('nama_user'),
            document.getElementById('tanggal'),
            document.getElementById('jenis_zakat'),
            document.getElementById('bentuk_zakat'),
            document.getElementById('keterangan')
        ];

        const nominalPerMustahikSection = document.getElementById('nominal_per_mustahik_section');
        const jumlahPerMustahikSection = document.getElementById('jumlah_per_mustahik_section');
        const nominalPerMustahikInput = document.getElementById('nominal_per_mustahik');
        const jumlahPerMustahikInput = document.getElementById('jumlah_per_mustahik');

        if (nominalPerMustahikSection.style.display !== 'none') {
            fields.push(nominalPerMustahikInput);
        }
        if (jumlahPerMustahikSection.style.display !== 'none') {
            fields.push(jumlahPerMustahikInput);
        }

        let filledFields = 0;
        let totalFields = fields.length;

        fields.forEach(field => {
            if (field && field.value && field.value.trim() !== '') {
                filledFields++;
            }
        });

        const progress = (totalFields > 0) ? (filledFields / totalFields) * 100 : 0;
        document.getElementById('form-progress').style.width = progress + '%';
        document.getElementById('progress-text').textContent = Math.round(progress) + '%';
    }

    // Inisialisasi saat dokumen siap
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi elemen
        const bentukZakat = document.getElementById('bentuk_zakat');
        const jenisZakat = document.getElementById('jenis_zakat');

        // Event listeners
        bentukZakat.addEventListener('change', () => {
            toggleFields();
        });

        jenisZakat.addEventListener('change', () => {
            // Pastikan perhitungan dijalankan saat jenis zakat berubah
            setTimeout(function() {
                hitungTotal();
            }, 50);
        });

        const allInputs = document.querySelectorAll('input, select, textarea');
        allInputs.forEach(input => {
            input.addEventListener('input', updateProgress);
            input.addEventListener('change', updateProgress);
        });

        // Inisialisasi awal
        toggleFields();
        updateProgress();

        // Pastikan perhitungan awal dijalankan
        setTimeout(function() {
            hitungJumlahMustahik();
        }, 200);
    });

    // Inisialisasi AutoNumeric
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi AutoNumeric untuk input nominal per mustahik
        const nominalPerMustahikInput = new AutoNumeric('#nominal_per_mustahik', {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 0,
            modifyValueOnWheel: false,
            unformatOnSubmit: false
        });

        // Event listener untuk recalculate saat input berubah
        nominalPerMustahikInput.node.addEventListener('autoNumeric:formatted', function() {
            hitungTotal();
        });
    });
</script>
@endsection