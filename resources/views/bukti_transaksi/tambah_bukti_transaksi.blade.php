@extends('layouts.app')

@section('content')
<div class="" style="padding: 0 5px 0 -5px;">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="fas fa-receipt fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Bukti Transaksi Pemasukan</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Infak</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">Bukti Transaksi Pemasukan</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">Bukti Transaksi</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Tambah Bukti Transaksi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Tambah Bukti Transaksi Baru</h4>
            <p class="text-dark-50 mb-3 small">Lengkapi form di bawah untuk menambahkan data transaksi infak</p>

            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('bukti_transaksi.index') }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="tambah-form" action="{{ route('bukti_transaksi.store') }}" method="POST" enctype="multipart/form-data">
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
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Tambah Bukti Transaksi
                    </div>
                    <!-- User dan tanggal -->
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label for="id_users" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>
                                User
                            </label>
                            <input type="text" class="form-control bg-light" id="nama_user" value="{{ $user->nama }}" readonly>
                            <input type="hidden" name="id_users" value="{{ $user->id }}">
                            @error('id_users')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_infak" class="form-label fw-bold text-dark">
                                <i class="bi bi-calendar2-range-fill me-2 text-primary"></i>
                                Tanggal<span class="text-danger"> *</span>
                            </label>
                            <input type="date" name="tanggal_infak" id="tanggal" class="form-control" value="{{ old('tanggal_infak') }}" required>
                            @error('tanggal_infak')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- donatur dan alamat -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="donatur" class="form-label fw-bold text-dark">
                                <i class="bi bi-person-circle me-2 text-primary"></i>
                                Donatur<span class="text-danger"> *</span>
                            </label>
                            <!-- Semua karakter selain huruf (a-z, A-Z) dan spasi akan dihapus (''). -->
                            <input oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" type="text" name="donatur" class="form-control" id="donatur" placeholder="Masukkan nama orang yang infak (donatur)" value="{{ old('donatur') }}" required>
                            @error('donatur')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alamat" class="form-label fw-bold text-dark">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                Alamat
                            </label>
                            <input type="text" name="alamat" class="form-control" id="alamat" placeholder="Masukkan alamat singkat donatur" value="{{ old('alamat') }}" required>
                            @error('alamat')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- nomor hp dan kategori -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nomor_telepon" class="form-label fw-bold text-dark">
                                <i class="fas fa-phone me-2 text-primary"></i>
                                Nomor HP
                            </label>
                            <!-- menampilkan input angka dari 0-9 
                            validasi setiap karakter yang bukan angka (0–9) akan otomatis dihapus -->
                            <input type="text"
                                name="nomor_telepon"
                                class="form-control"
                                id="nomor_telepon"
                                placeholder="Masukkan nomor telepon donatur maks 12 angka"
                                inputmode="numeric" 
                                pattern="[0-9]+"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                value="{{ old('nomor_telepon') }}" required>
                            @error('nomor_telepon')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label fw-bold text-dark">
                                <i class="fas fa-layer-group me-2 text-primary"></i>
                                Kategori<span class="text-danger"> *</span>
                            </label>
                            <select name="kategori" class="form-select" id="kategori" required>
                                <option disabled selected>Pilih Kategori</option>
                                <option value="Pembangunan" {{ old('kategori') == 'Pembangunan' ? 'selected' : '' }}>Pembangunan</option>
                                <option value="Takmir" {{ old('kategori') == 'Takmir' ? 'selected' : '' }}>Takmir</option>
                            </select>
                            @error('kategori')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- sumber dan metode -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sumber" class="form-label fw-bold text-dark">
                                <i class="fas fa-wallet me-2 text-primary"></i>
                                Sumber<span class="text-danger"> *</span>
                            </label>
                            <select name="sumber" class="form-select" id="sumber" required>
                                <option disabled selected>Pilih Sumber</option>
                                <option value="Donatur" {{ old('sumber') == 'Donatur' ? 'selected' : '' }}>Donatur</option>
                                <option value="Kotak Amal" {{ old('sumber') == 'Kotak Amal' ? 'selected' : '' }}>Kotak Amal</option>
                            </select>
                            @error('sumber')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="metode" class="form-label fw-bold text-dark">
                                <i class="fas fa-receipt me-2 text-primary"></i>
                                Metode<span class="text-danger"> *</span>
                            </label>
                            <select name="metode" class="form-select" id="metode" required>
                                <option disabled selected>Pilih Metode</option>
                                <option value="Tunai (Langsung)" {{ old('metode') == 'Tunai (Langsung)' ? 'selected' : '' }}>Tunai (Langsung)</option>
                                <option value="Transfer" {{ old('metode') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                            </select>
                            @error('metode')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- jenis infak dan nominal -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jenis_infak" class="form-label fw-bold text-dark">
                                <i class="bi bi-box2-fill me-2 text-primary"></i>
                                Jenis Infak<span class="text-danger"> *</span>
                            </label>
                            <select name="jenis_infak" class="form-select" id="jenis_infak" required>
                                <option value="Uang" {{ old('jenis_infak', 'Uang') == 'Uang' ? 'selected' : '' }}>Uang</option>
                                <option value="Barang" {{ old('jenis_infak') == 'Barang' ? 'selected' : '' }}>Barang</option>
                            </select>
                            @error('jenis_infak')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3" id="nominal_section">
                            <label for="nominal" class="form-label fw-bold text-dark">
                                <i class="bi bi-cash-coin me-2 text-primary"></i>
                                Nominal (Rp)<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="nominal" class="form-control" id="nominal"
                                placeholder="Masukkan nominal infak, contoh 40000" value="{{ old('nominal') }}" required>
                            @error('nominal')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- barang dan bukti infak -->
                    <div class="row">
                        <div class="col-md-6 mb-3" id="barang_section">
                            <label for="barang" class="form-label fw-bold text-dark">
                                <i class="bi bi-box-seam-fill me-2 text-primary"></i>
                                Barang<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="barang" id="barang" class="form-control" placeholder="Masukkan barang yang diinfakkan donatur" value="{{ old('barang') }}">
                            @error('barang')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bukti_transaksi" class="form-labe fw-bold text-dark">
                                <i class="fas fa-receipt me-2 text-primary"></i>
                                Bukti Transaksi Pemasukan<span class="text-danger"> *</span>
                            </label>
                            <input type="file" name="bukti_transaksi" id="bukti_transaksi_pemasukan" class="form-control">
                            <small class="text-muted"> Bukti jpg,png,jpeg max:10240 </small>
                            <div class="mt-2">
                                <img id="preview-gambar" src="#" alt="Preview Gambar" class="img-thumbnail d-none" style="max-height: 93px;">
                            </div>
                            @error('bukti_transaksi')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- keterangan -->
                    <div class="row">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-bold text-dark">
                                <i class="bi bi-card-text me-2 text-primary"></i>
                                Keterangan<span class="text-danger"> *</span>
                            </label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Keterangan infak">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary" onclick="konfirmasiTambah()">
                            <i class="bi bi-save-fill me-1"></i>
                            Simpan
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
    function konfirmasiTambah() {
        Swal.fire({
            title: "Simpan data bukti transaksi?",
            text: "Apakah Anda yakin ingin menyimpan data bukti transaksi ini?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#0d6efd",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, simpan",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                // Unformat nominal sebelum submit
                const nominalInput = AutoNumeric.getAutoNumericElement('#nominal');
                if (nominalInput) {
                    nominalInput.unformat();
                }

                document.getElementById("tambah-form").submit();
            }
        });
    }
    // Update otomatis isi textarea keterangan saat donatur diisi
    // document.querySelector('input[name="donatur"]').addEventListener('input', function() {
    //     const namaDonatur = this.value.trim();
    //     const keteranganField = document.getElementById('keterangan');

    //     if (namaDonatur !== '') {
    //         keteranganField.value = 'Infak atas nama ' + namaDonatur;
    //     } else {
    //         keteranganField.value = '';
    //     }
    //     updateProgress();
    // });

    // Update progress bar
    // Inisialisasi elemen global
    const fields = [
        document.getElementById('nama_user'),
        document.getElementById('tanggal'),
        document.getElementById('donatur'),
        document.getElementById('alamat'),
        document.getElementById('nomor_telepon'),
        document.getElementById('kategori'),
        document.getElementById('metode'),
        document.getElementById('jenis_infak'),
        document.getElementById('nominal'),
        document.getElementById('barang'),
        document.getElementById('bukti_transaksi_pemasukan'),
        document.getElementById('keterangan'),
        document.getElementById('sumber')
    ];
    const progressBar = document.getElementById('form-progress');
    const progressText = document.getElementById('progress-text');
    const jenisInfak = document.getElementById('jenis_infak');
    const nominalSection = document.getElementById('nominal_section');
    const barangSection = document.getElementById('barang_section');

    // Fungsi untuk update progress bar
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

    // Fungsi toggle kolom
    function toggleFields() {
        const value = jenisInfak.value.toLowerCase();

        if (value === 'barang') {
            nominalSection.style.display = 'none';
            barangSection.style.display = 'block';
        } else if (value === 'uang') {
            nominalSection.style.display = 'block';
            barangSection.style.display = 'none';
        } else {
            nominalSection.style.display = 'block';
            barangSection.style.display = 'block';
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
        jenisInfak.addEventListener('change', toggleFields);
    });
</script>

<!-- menampilkan gambar -->
<script>
    // Preview gambar saat dipilih
    document.getElementById("bukti_transaksi_pemasukan").addEventListener("change", function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById("preview-gambar");

        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove("d-none");
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = "#";
            preview.classList.add("d-none");
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi AutoNumeric untuk input nominal
        const nominalInput = new AutoNumeric('#nominal', {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 2,
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