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
                    <span class="text-success fw-semibold">Tambah infak keluar</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Tambah Pengeluaran Infak Baru</h4>
            <p class="text-dark-50 mb-3 small">Lengkapi form di bawah untuk menambahkan data infak masjid</p>

            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('infak_keluar.index', ['kategori' => $kategori]) }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="tambah-form" action="{{ route('infak_keluar.store') }}" method="POST" enctype="multipart/form-data">
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
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Tambah Infak Keluar
                    </div>
                    <div class="row mt-3">
                        <!-- User dan Tanggal -->
                        <div class="col-md-6 mb-3">
                            <label for="id_user" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>
                                User
                            </label>
                            <input type="text" class="form-control bg-light" id="nama_user" value="{{ $user->nama }}" readonly>
                            <input type="hidden" name="id_user" value="{{ $user->id }}">
                            @error('id_user')
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
                        <!-- Kategori dan Nominal -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-layer-group me-2 text-primary"></i>
                                Kategori
                            </label>
                            <input type="text" class="form-control bg-light" id="kategori" value="{{ ucfirst($kategori) }}" readonly>
                            <input type="hidden" name="kategori" value="{{ $kategori }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nominal" class="form-label fw-bold text-dark">
                                <i class="bi bi-cash-coin me-2 text-primary"></i>
                                Nominal (Rp)<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="nominal" id="nominal" class="form-control" placeholder="Masukkan harga barang, contoh 40000" value="{{ old('nominal') }}" required>
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
                                <i class="bi bi-box-seam-fill me-2 text-primary"></i>
                                Barang<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="barang" id="barang" class="form-control" placeholder="Masukkan barang yang dibeli" value="{{ old('barang') }}" required>
                            @error('barang')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bukti_infak_keluar" class="form-label fw-bold text-dark">
                                <i class="fas fa-receipt me-2 text-primary"></i>
                                Bukti Infak Keluar<span class="text-danger"> *</span>
                            </label>

                            <input type="file" name="bukti_infak_keluar" id="bukti_infak_keluar" class="form-control" required>
                            <small class="text-muted">Bukti jpg, png, jpeg. Maks 10Mb</small>

                            {{-- Jika sebelumnya gagal simpan, munculkan pesan upload ulang --}}
                            @if(session('temp_bukti_infak_keluar'))
                            <div class="text-warning small mt-1">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Bukti infak keluar sebelumnya
                            </div>
                            @endif

                            {{-- Preview Gambar --}}
                            <div class="mt-2">
                                <img id="preview-gambar"
                                    src="{{ session('temp_bukti_infak_keluar') ? Storage::url(session('temp_bukti_infak_keluar')) : '#' }}"
                                    alt="Preview Gambar"
                                    class="img-thumbnail {{ session('temp_bukti_infak_keluar') ? '' : 'd-none' }}"
                                    style="max-height: 93px;">
                                <input type="hidden" name="temp_bukti_infak_keluar" value="{{ session('temp_bukti_infak_keluar') }}">
                            </div>

                            @error('bukti_infak_keluar')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-bold text-dark">
                            <i class="bi bi-card-text me-2 text-primary"></i>
                            Keterangan<span class="text-danger"> *</span>
                        </label>
                        <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan keterangan detail pengeluaran infak untuk apa" rows="3" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-primary" onclick="validasiSebelumKonfirmasi()">
                            <i class="bi bi-save-fill me-1"></i>
                            Simpan
                        </button>
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

        const requiredFields = [
            'tanggal', 'kategori', 'nominal', 'barang', 'bukti_infak_keluar', 'keterangan'
        ];

        let firstInvalidField = null;

        for (const fieldId of requiredFields) {
            const field = document.getElementById(fieldId);
            let isEmpty = false;

            if (fieldId === 'bukti_infak_keluar') {
                const file = field.files[0];
                const hasTemp = document.querySelector('input[name="temp_bukti_infak_keluar"]')?.value;
                if (!file && !hasTemp) {
                    isEmpty = true;
                }
            } else {
                const val = field?.value?.trim();
                isEmpty = !val;
            }

            if (isEmpty) {
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'dynamic-error');
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
            title: "Simpan data infak keluar?",
            text: "Apakah Anda yakin ingin menyimpan data infak keluar ini?",
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
                // Submit form
                form.submit();
            }
        });
    }
</script>

<script>
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

<!-- menampilkan gambar -->
<script>
    // Preview gambar saat dipilih
    document.getElementById("bukti_infak_keluar").addEventListener("change", function(event) {
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const preview = document.getElementById("preview-gambar");
        const fileInput = document.getElementById("bukti_infak_keluar");

        fileInput.addEventListener("change", function(event) {
            const file = event.target.files[0];
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
    });
</script>


@endsection