@extends('layouts.app')

@section('content')
<div class="" style="padding: 0 5px 0 -5px;">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <!-- {{-- Cek kategori untuk menentukan ikon --}} -->
                    @if (strtolower($kategori) == 'pembangunan')
                    <i class="fas fa-mosque fs-4"></i>
                    @elseif (strtolower($kategori) == 'takmir')
                    <i class="fas fa-people-group fs-4"></i>
                    @else
                    <!-- {{-- default icon jika kategori tidak dikenali --}} -->
                    <i class="fas fa-donate fs-4"></i>
                    @endif
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Infak Keluar {{ ucfirst($kategori) }} Masjid</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Infak </span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">{{ ucfirst($kategori) }} </span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">Pengeluaran</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Edit infak keluar</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Edit Data Infak Keluar</h4>
            <p class="text-dark-50 mb-3 small">Ubah data pada form di bawah untuk update data infak masjid</p>
            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('infak_keluar.index', ['kategori' => $kategori]) }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="edit-form" action="{{ route('infak_keluar.update', $infakKeluar->id) }}" method="POST" enctype="multipart/form-data">
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
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Edit Infak Keluar
                    </div>
                    <div class="row mt-3">
                        <!-- User dan Tanggal -->
                        <div class="col-md-6 mb-3">
                            <label for="id_user" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-warning"></i>
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
                                <i class="bi bi-calendar2-range-fill me-2 text-warning"></i>
                                Tanggal<span class="text-danger"> *</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required value="{{ old('tanggal', $infakKeluar->tanggal) }}">
                            @error('tanggal')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Kategori dan Nominal -->
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label fw-bold text-dark">
                                <i class="fas fa-layer-group me-2 text-warning"></i>
                                Kategori
                            </label>
                            <input type="text" class="form-control bg-light" id="kategori" value="{{ ucfirst($kategori) }}" readonly>
                            <input type="hidden" name="kategori" value="{{ $kategori }}">
                            @error('kategori')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nominal" class="form-label fw-bold text-dark">
                                <i class="bi bi-cash-coin me-2 text-warning"></i>
                                Nominal (Rp)<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="nominal" id="nominal" class="form-control"
                                value="{{ old('nominal', ($infakKeluar->nominal)) }}">
                            <small class="text-muted">Sisa Saldo {{ ucfirst($kategori) }}:</small> <strong> {{ number_format($sisaSaldo, 0, ',', '.') }}</strong>
                            @error('nominal')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Barang dan Bukti -->
                        <div class="col-md-6 mb-3">
                            <label for="barang" class="form-label fw-bold text-dark">
                                <i class="bi bi-box-seam-fill me-2 text-warning"></i>
                                Barang<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="barang" id="barang" class="form-control" value="{{ old('barang', $infakKeluar->barang) }}">
                            @error('barang')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bukti_infak_keluar" class="form-label fw-bold text-dark">
                                <i class="fas fa-receipt me-2 text-warning"></i>
                                Bukti Infak Keluar <span class="text-danger">*</span>
                            </label>

                            <!-- Input file -->
                            <input type="file" name="bukti_infak_keluar" id="bukti_infak_keluar"
                                class="form-control @error('bukti_infak_keluar') is-invalid @enderror"
                                accept="image/*" onchange="previewBukti(event)">

                            @error('bukti_infak_keluar')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <!-- Preview container -->
                            <div class="mt-2" id="preview-container">
                                @if(session('temp_bukti_infak_keluar'))
                                <!-- Jika ada gambar dari session -->
                                <small class="text-warning">Bukti baru (belum disimpan)</small>
                                <div id="preview-wrapper">
                                    <img id="preview-gambar" src="{{ asset('storage/' . session('temp_bukti_infak_keluar')) }}"
                                        class="img-thumbnail" style="max-height: 150px;" alt="Preview Bukti Baru">
                                    <div class="small text-muted">Preview bukti baru</div>
                                </div>
                                @elseif($infakKeluar->bukti_infak_keluar)
                                <!-- Jika ada gambar lama dan tidak ada session -->
                                <small class="text-success">Bukti infak saat ini</small>
                                <div id="gambar-bukti-lama">
                                    <img src="{{ asset('storage/' . $infakKeluar->bukti_infak_keluar) }}" alt="Bukti Lama"
                                        class="img-thumbnail" style="max-height: 150px;">
                                </div>
                                <!-- Container preview baru, tapi disembunyikan dulu -->
                                <div class="d-none" id="preview-wrapper">
                                    <small class="text-warning">Preview bukti baru</small>
                                    <div>
                                        <img id="preview-gambar" class="img-thumbnail" style="max-height: 150px;" alt="Preview Bukti Baru">
                                        <div class="small text-muted">Preview bukti baru</div>
                                    </div>
                                </div>
                                @else
                                <!-- Tidak ada gambar sama sekali -->
                                <div class="d-none" id="preview-wrapper">
                                    <small class="text-warning">Preview bukti baru</small>
                                    <div>
                                        <img id="preview-gambar" class="img-thumbnail" style="max-height: 150px;" alt="Preview Bukti Baru">
                                        <div class="small text-muted">Preview bukti baru</div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-text">Lewati jika tidak ingin mengubah. Format PNG, JPG, JPEG. Maksimal 10240.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-bold text-dark">
                            <i class="bi bi-card-text me-2 text-warning"></i>
                            Keterangan<span class="text-danger"> *</span>
                        </label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3">{{ old('keterangan', $infakKeluar->keterangan) }}</textarea>
                        @error('keterangan')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-warning" onclick="konfirmasiEdit()">
                            <i class="bi bi-save-fill me-1"></i>
                            Edit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function konfirmasiEdit() {
        Swal.fire({
            title: "Simpan perubahan data infak keluar?",
            text: "Apakah Anda yakin ingin menyimpan perubahan data infak keluar ini?",
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
    // Update progress bar
    // Definisi fungsi
    function updateProgress() {
        let filledFields = 0;
        const totalFields = fields.length;

        fields.forEach(field => {
            if (field.type === 'file') {
                // Hitung sebagai terisi jika sudah ada file baru ATAU file lama (data-has-file)
                if (field.files.length > 0 || field.hasAttribute('data-has-file')) {
                    filledFields++;
                }
            } else if (field.value && field.value.trim() !== '') {
                filledFields++;
            }
        });

        const progress = (filledFields / totalFields) * 100;
        progressBar.style.width = progress + '%';
        progressText.textContent = Math.round(progress) + '%';
    }



    // Inisialisasi elemen & event listener
    const fields = [
        document.getElementById('nama_user'),
        document.getElementById('tanggal'),
        document.getElementById('kategori'),
        document.getElementById('nominal'),
        document.getElementById('barang'),
        document.getElementById('bukti_infak_keluar'),
        document.getElementById('keterangan')
    ];
    const progressBar = document.getElementById('form-progress');
    const progressText = document.getElementById('progress-text');

    fields.forEach(field => {
        field.addEventListener('input', updateProgress);
        field.addEventListener('change', updateProgress); // untuk <select>
    });

    document.addEventListener('DOMContentLoaded', updateProgress);
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

<script>
    function previewBukti(event) {
        const input = event.target;
        const previewWrapper = document.getElementById('preview-wrapper');
        const previewImg = document.getElementById('preview-gambar');
        const gambarLama = document.getElementById('gambar-bukti-lama');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewWrapper.classList.remove('d-none');
                if (gambarLama) gambarLama.classList.add('d-none'); // sembunyikan gambar lama
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection