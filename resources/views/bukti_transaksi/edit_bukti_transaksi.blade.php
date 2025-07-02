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
                    <span class="text-success fw-semibold">Edit Bukti Transaksi</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Edit Data Bukti Transaksi Infak</h4>
            <p class="text-dark-50 mb-3 small">Ubah data pada form di bawah untuk update data transaksi infak</p>
            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('bukti_transaksi.index') }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="edit-form" action="{{ route('bukti_transaksi.update', $buktiTransaksi->id) }}" method="POST" enctype="multipart/form-data">
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
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Edit Bukti Transaksi Infak
                    </div>
                    <!-- User dan tanggal -->
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label for="id_user" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-warning"></i>
                                User
                            </label>
                            <input type="text" class="form-control bg-light" id="nama_user" value="{{ $user->nama }}" readonly>
                            <input type="hidden" name="id_users" value="{{ $user->id }}">
                            @error('users')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_infak" class="form-label fw-bold text-dark">
                                <i class="bi bi-calendar2-range-fill me-2 text-warning"></i>
                                Tanggal<span class="text-danger"> *</span>
                            </label>
                            <input type="date" name="tanggal_infak" class="form-control" id="tanggal" value="{{ $buktiTransaksi->tanggal_infak }}" required>
                            @error('tanggal_infak')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- donatur dan alamat -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="donatur" class="form-label fw-bold text-dark">
                                <i class="bi bi-person-circle me-2 text-warning"></i>
                                Donatur<span class="text-danger"> *</span>
                            </label>
                            <input oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" type="text" name="donatur" class="form-control" id="donatur" value="{{ $buktiTransaksi->donatur }}" required>
                            @error('donatur')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alamat" class="form-label fw-bold text-dark">
                                <i class="fas fa-map-marker-alt me-2 text-warning"></i>
                                Alamat
                            </label>
                            <input type="text" name="alamat" class="form-control" id="alamat" value="{{ $buktiTransaksi->alamat }}" required>
                            @error('alamat')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- nomor hp dan kategori -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nomor_telepon" class="form-label fw-bold text-dark">
                                <i class="fas fa-phone me-2 text-warning"></i>
                                Nomor HP
                            </label>
                            <input type="text" name="nomor_telepon" class="form-control" id="nomor_telepon" inputmode="numeric"
                                pattern="[0-9]+"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ $buktiTransaksi->nomor_telepon }}" required>
                            @error('nomor_telepon')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label fw-bold text-dark">
                                <i class="fas fa-layer-group me-2 text-warning"></i>
                                Kategori<span class="text-danger"> *</span>
                            </label>
                            <select name="kategori" class="form-select" id="kategori" required>
                                <option disabled>Pilih Kategori</option>
                                <option value="Pembangunan" {{ $buktiTransaksi->kategori == 'Pembangunan' ? 'selected' : '' }}>Pembangunan</option>
                                <option value="Takmir" {{ $buktiTransaksi->kategori == 'Takmir' ? 'selected' : '' }}>Takmir</option>
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
                                <i class="fas fa-wallet me-2 text-warning"></i>
                                Sumber<span class="text-danger"> *</span>
                            </label>
                            <select name="sumber" class="form-select" id="sumber" required>
                                <option disabled>Pilih Sumber</option>
                                <option value="Donatur" {{ $buktiTransaksi->sumber == 'Donatur' ? 'selected' : '' }}>Donatur</option>
                                <option value="Kotak Amal" {{ $buktiTransaksi->sumber == 'Kotak Amal' ? 'selected' : '' }}>Kotak Amal</option>
                            </select>
                            @error('sumber')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="metode" class="form-label fw-bold text-dark">
                                <i class="fas fa-receipt me-2 text-warning"></i>
                                Metode<span class="text-danger"> *</span>
                            </label>
                            <select name="metode" class="form-select" id="metode" required>
                                <option disabled>Pilih Metode</option>
                                <option value="Tunai (Langsung)" {{ $buktiTransaksi->metode == 'Tunai' ? 'selected' : '' }}>Tunai (Langsung)</option>
                                <option value="Transfer" {{ $buktiTransaksi->metode == 'Transfer' ? 'selected' : '' }}>Transfer</option>
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
                                <i class="bi bi-box2-fill me-2 text-warning"></i>
                                Jenis Infak<span class="text-danger"> *</span>
                            </label>
                            <select name="jenis_infak" class="form-select" id="jenis_infak" required>
                                <option value="Uang" {{ old('jenis_infak', $buktiTransaksi->jenis_infak) == 'Uang' ? 'selected' : '' }}>Uang</option>
                                <option value="Barang" {{ old('jenis_infak', $buktiTransaksi->jenis_infak) == 'Barang' ? 'selected' : '' }}>Barang</option>
                            </select>
                            <small class="text-muted"> Jika akan ubah jenis infak hapus terlebih dahulu nominal / barang yang telah diisi </small>
                            @error('jenis_infak')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3" id="nominal_section">
                            <label for="nominal" class="form-label fw-bold text-dark">
                                <i class="bi bi-cash-coin me-2 text-warning"></i>
                                Nominal (Rp)<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="nominal" id="nominal" class="form-control"
                                value="{{ old('nominal', intval($buktiTransaksi->nominal)) }}">
                            @error('nominal')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- barang dan bukti infak -->
                    <div class="row">
                        <div class="col-md-6 mb-3" id="barang_section">
                            <label for="barang" class="form-label fw-bold text-dark">
                                <i class="bi bi-box-seam-fill me-2 text-warning"></i>
                                Barang<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="barang" class="form-control" id="barang" value="{{ $buktiTransaksi->barang }}">
                            @error('barang')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bukti_transaksi_pemasukan" class="form-label fw-bold text-dark">
                                <i class="fas fa-receipt me-2 text-primary"></i>
                                Bukti Transaksi Pemasukan<span class="text-danger"> *</span>
                            </label>

                            <input type="file" name="bukti_transaksi" id="bukti_transaksi_pemasukan" class="form-control"
                                onchange="previewImage(event)">

                            @error('bukti_transaksi')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror

                            @if($buktiTransaksi->bukti_transaksi)
                            <small class="text-success">Bukti sudah tersedia</small>
                            <div class="mt-2">
                                <img id="preview-gambar" src="{{ asset('storage/' . $buktiTransaksi->bukti_transaksi) }}" alt="Preview Gambar" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                            @endif
                        </div>

                    </div>
                    <!-- keterangan -->
                    <div class="row">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-bold text-dark">
                                <i class="bi bi-card-text me-2 text-warning"></i>
                                Keterangan<span class="text-danger"> *</span>
                            </label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Keterangan infak">{{ old('keterangan', $buktiTransaksi->keterangan) }}</textarea>
                            @error('keterangan')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-warning" onclick="konfirmasiEdit()">
                            <i class="bi bi-save-fill me-1"></i>
                            Edit
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
    function konfirmasiEdit() {
        Swal.fire({
            title: "Simpan perubahan?",
            text: "Apakah Anda yakin ingin menyimpan perubahan data ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, edit",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                const nominalInput = AutoNumeric.getAutoNumericElement('#nominal');
                if (nominalInput) {
                    const unformattedValue = nominalInput.getNumber();
                    // Set value input ke angka murni (string)
                    document.getElementById('nominal').value = unformattedValue !== null ? unformattedValue.toString() : '';
                }

                document.getElementById("edit-form").submit();
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

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('preview-gambar');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = ""; // Reset if no file is selected
        }
    }
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