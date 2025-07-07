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
                                <input type="date" name="tanggal" id="tanggal" class="form-control" required value="{{ old('tanggal') }}">
                                @error('tanggal')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- mustahik bisa multiple -->
                        <div class="row">
                            <label for="id_user" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>
                                Mustahik
                            </label>
                            <!-- Mustahik dan Jenis Zakat -->
                            <!-- Bagian Mustahik -->
                            <div id="mustahik-wrapper">
                                @php
                                $oldMustahik = old('id_mustahik', []);
                                @endphp
                                @if (count($oldMustahik) > 0)

                                <!-- Jika sebelumnya ada data mustahik dari validasi -->
                                @foreach ($oldMustahik as $index => $id)
                                <div class="row mustahik-group mb-3">
                                    <div class="col-md-11">
                                        <select name="id_mustahik[]" class="form-control select2-mustahik" required>
                                            <option value="" disabled>Pilih Mustahik</option>
                                            @foreach ($mustahik as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Tombol hapus mustahik -->
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-mustahik p-1" style="font-size: 0.8rem; height: 30px; width: 30px;">
                                            <i class="bi bi-x-circle-fill" style="font-size: 14px;"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                                @else

                                <!-- Form default jika belum ada input sebelumnya -->
                                <div class="row mustahik-group mb-3">
                                    <div class="col-md-11">
                                        <select name="id_mustahik[]" class="form-control select2-mustahik" required>
                                            <option value="" disabled selected>Pilih Mustahik</option>
                                            @foreach ($mustahik as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Tombol hapus mustahik -->
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-mustahik p-1" style="font-size: 0.8rem; height: 30px; width: 30px;">
                                            <i class="bi bi-x-circle-fill" style="font-size: 14px;"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Tambah Mustahik -->
                        <div class="mb-3">
                            <button type="button" id="btn-tambah-mustahik" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-circle-fill me-1"></i> Tambah Mustahik
                            </button>
                            <small class="text-muted ms-2">
                                Jumlah Mustahik Dipilih: <span id="jumlah-mustahik" class="fw-bold">0</span>
                            </small>
                        </div>

                        <!-- Jenis dan Bentuk Zakat -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_zakat" class="form-label fw-bold text-dark">
                                    <i class="fas fa-boxes-stacked me-2 text-primary"></i>
                                    Jenis Zakat<span class="text-danger"> *</span>
                                </label>
                                <select name="jenis_zakat" id="jenis_zakat" class="form-select" required>
                                    <option value="" disabled selected>Pilih Jenis Zakat</option>
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
                                <select name="bentuk_zakat" id="bentuk_zakat" class="form-select" required>
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
                            <button type="button" class="btn btn-primary" onclick="konfirmasiTambah()">
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
    $(document).ready(function() {
        // inisialisasi select2 untuk elemen mustahik
        function refreshSelect2() {
            $('.select2-mustahik').select2({
                placeholder: "Pilih Mustahik",
                width: '100%'
            });
        }

        refreshSelect2();

        // fungsi untuk menghitung jumlah mustahik
        function hitungJumlahMustahik() {
            const mustahikSelects = document.querySelectorAll('select[name="id_mustahik[]"]');
            let jumlahMustahik = 0;

            mustahikSelects.forEach(select => {
                if (select.value && select.value !== '') {
                    jumlahMustahik++;
                }
            });

            document.getElementById('jumlah-mustahik').textContent = jumlahMustahik;
            hitungTotal(); // panggil fungsi hitungTotal untuk memperbarui total
            return jumlahMustahik;
        }

        // menyembunyikan tombol remove untuk mustahik pertama, tampilkan untuk yang lainnya
        function updateRemoveButtons() {
            const mustahikGroups = document.querySelectorAll('.mustahik-group');

            mustahikGroups.forEach((group, index) => {
                const removeButton = group.querySelector('.btn-remove-mustahik');
                if (removeButton) {
                    // Sembunyikan tombol remove untuk mustahik pertama (index 0)
                    // atau jika hanya ada 1 mustahik
                    if (index === 0 || mustahikGroups.length === 1) {
                        removeButton.style.display = 'none';
                    } else {
                        removeButton.style.display = 'block';
                    }
                }
            });
        }

        // fungsi untuk menghitung total zakat keluar berdasarkan jumlah mustahik dan input per mustahik
        function hitungTotal() {
            const jumlahMustahik = parseInt(document.getElementById('jumlah-mustahik').textContent) || 0;
            const bentukZakat = document.getElementById('bentuk_zakat').value;
            const fieldContainer = document.getElementById('field-nominal-jumlah');
            fieldContainer.innerHTML = ''; // kosongkan field sebelum menambahkan input baru

            if (bentukZakat === 'Uang') {
                const nominalPerMustahik = AutoNumeric.getNumber('#nominal_per_mustahik') || 0;
                const totalNominal = nominalPerMustahik * jumlahMustahik;

                for (let i = 0; i < jumlahMustahik; i++) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'nominal[]';
                    input.value = nominalPerMustahik;
                    fieldContainer.appendChild(input);
                }

                // tampilkan ringkasan perhitungan
                document.getElementById('perhitungan-text').textContent =
                    `${jumlahMustahik} mustahik × Rp ${nominalPerMustahik.toLocaleString('id-ID')} = Rp ${totalNominal.toLocaleString('id-ID')}`;

            } else if (bentukZakat === 'Beras') {
                const jumlahPerMustahik = parseFloat(document.getElementById('jumlah_per_mustahik').value) || 0;
                const totalJumlah = jumlahPerMustahik * jumlahMustahik;

                for (let i = 0; i < jumlahMustahik; i++) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'jumlah[]';
                    input.value = jumlahPerMustahik;
                    fieldContainer.appendChild(input);
                }

                // tampilkan ringkasan perhitungan
                document.getElementById('perhitungan-text').textContent =
                    `${jumlahMustahik} mustahik × ${jumlahPerMustahik} kg = ${totalJumlah} kg`;
            }
        }

        // tambah input baru untuk mustahik
        $('#btn-tambah-mustahik').on('click', function() {
            const newGroup = `
           

             <div class="row mustahik-group mb-3">
                                     <div class="col-md-11">
                            <select name="id_mustahik[]" class="form-control select2-mustahik" required>
                                <option value="" disabled selected>Pilih Mustahik</option>
                                @foreach ($mustahik as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-mustahik p-1" style="font-size: 0.8rem; height: 30px; width: 30px;">
                                            <i class="bi bi-x-circle-fill" style="font-size: 14px;"></i>
                                        </button>
                                    </div>
                                </div>
        `;

            $('#mustahik-wrapper').append(newGroup);
            refreshSelect2();

            // Update visibility tombol remove setelah menambah mustahik baru
            updateRemoveButtons();
        });

        // hapus input mustahik
        $(document).on('click', '.btn-remove-mustahik', function() {
            const mustahikGroup = $(this).closest('.mustahik-group');
            const isFirstGroup = mustahikGroup.is('.mustahik-group:first');

            // Jangan hapus jika ini adalah mustahik pertama
            if (isFirstGroup) {
                return;
            }

            mustahikGroup.remove();
            hitungJumlahMustahik();

            // Update visibility tombol remove setelah menghapus
            updateRemoveButtons();
        });

        // event listener untuk perubahan select mustahik
        $(document).on('change', 'select[name="id_mustahik[]"]', function() {
            hitungJumlahMustahik();
        });

        // event listener untuk input per mustahik
        $(document).on('input change', '#nominal_per_mustahik, #jumlah_per_mustahik', function() {
            hitungTotal();
        });

        // inisialisasi awal
        hitungJumlahMustahik();
        updateRemoveButtons(); // panggil fungsi ini untuk mengatur tombol remove pada load awal
    });
</script>

<script>
    function konfirmasiTambah() {
        // validasi apakah sudah ada mustahik yang dipilih
        const jumlahMustahik = parseInt(document.getElementById('jumlah-mustahik').textContent);
        if (jumlahMustahik === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Silakan pilih minimal 1 mustahik terlebih dahulu.',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        // validasi input per mustahik
        const bentukZakat = document.getElementById('bentuk_zakat').value;
        let inputPerMustahik = 0;

        if (bentukZakat === 'Uang') {
            inputPerMustahik = AutoNumeric.getNumber('#nominal_per_mustahik') || 0;
        } else if (bentukZakat === 'Beras') {
            inputPerMustahik = parseFloat(document.getElementById('jumlah_per_mustahik').value) || 0;
        }

        if (inputPerMustahik === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Silakan masukkan jumlah zakat per mustahik.',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        // konfirmasi sebelum submit
        Swal.fire({
            title: "Simpan data zakat keluar?",
            html: `
                <p>Apakah Anda yakin ingin menyimpan data zakat keluar ini?</p>
            `,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, simpan",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                // Unformat nominal sebelum submit
                const nominalPerMustahikInput = AutoNumeric.getAutoNumericElement('#nominal_per_mustahik');
                if (nominalPerMustahikInput) {
                    nominalPerMustahikInput.unformat();
                }

                document.getElementById("tambah-form").submit();
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi elemen
        const bentukZakat = document.getElementById('bentuk_zakat');
        const nominalPerMustahikSection = document.getElementById('nominal_per_mustahik_section');
        const jumlahPerMustahikSection = document.getElementById('jumlah_per_mustahik_section');
        const nominalPerMustahikInput = document.getElementById('nominal_per_mustahik');
        const jumlahPerMustahikInput = document.getElementById('jumlah_per_mustahik');
        const sisaSaldoSpan = document.getElementById('sisa-saldo');

        // Fungsi untuk mengatur field berdasarkan bentuk zakat
        function toggleFields() {
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

                // Update perhitungan text
                document.getElementById('perhitungan-text').textContent = '0 mustahik × 0 kg = 0 kg';

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

                // Update perhitungan text
                document.getElementById('perhitungan-text').textContent = '0 mustahik × Rp 0 = Rp 0';
            }

            // Update progress dan perhitungan setelah toggle
            updateProgress();
            if (typeof hitungTotal === 'function') {
                hitungTotal();
            }
        }

        // Progress bar functionality
        function updateProgress() {
            const fields = [
                document.getElementById('nama_user'),
                document.getElementById('id_mustahik'),
                document.getElementById('tanggal'),
                document.getElementById('jenis_zakat'),
                document.getElementById('bentuk_zakat'),
                document.getElementById('keterangan')
            ];

            // Tambahkan field yang sedang aktif
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

        // Event listeners
        bentukZakat.addEventListener('change', toggleFields);

        // Event listeners untuk progress
        const allInputs = document.querySelectorAll('input, select, textarea');
        allInputs.forEach(input => {
            input.addEventListener('input', updateProgress);
            input.addEventListener('change', updateProgress);
        });

        // Inisialisasi awal
        toggleFields();
        updateProgress();
    });
</script>

<script>
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
            if (typeof hitungTotal === 'function') {
                hitungTotal();
            }
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