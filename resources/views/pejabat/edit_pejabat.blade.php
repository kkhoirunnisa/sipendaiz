@extends('layouts.app')

@section('content')
<div class="" style="padding: 0 5px 0 -5px;">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="bi bi-person-badge-fill fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Pengurus</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Users</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">Pengurus</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Edit Pengurus</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-dark mb-1 fw-bold">Edit Data Pengurus</h4>
            <p class="text-dark-50 mb-3 small">Ubah data pada form di bawah untuk update data pejabat masjid</p>
            <a class="btn btn-success align-items-center gap-2 mb-2" href="{{ route('pejabat.index') }}">
                <i class="bi bi-arrow-left-circle-fill"></i> Kembali
            </a>

            <form id="edit-form" action="{{ route('pejabat.update', $pejabat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                {{-- Tambahkan input hidden untuk gambar sementara --}}
                @if(session('temp_foto_ttd'))
                <input type="hidden" name="foto_ttd_temp" value="{{ session('temp_foto_ttd') }}">
                @endif
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-warning text-dark fw-bold">
                        <i class="bi bi-clipboard-fill me-2"></i>Formulir Edit Pengurus
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
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

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">
                                <i class="fas fa-briefcase me-2 text-warning"></i>
                                Jabatan
                            </label>
                            <input type="text" class="form-control" value="{{ ucwords(str_replace('_', ' ', $pejabat->jabatan)) }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold text-dark">
                                <i class="fas fa-user me-2 text-warning"></i>
                                Nama<span class="text-danger"> *</span>
                            </label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $pejabat->nama) }}" required>
                            @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input file untuk mengunggah tanda tangan pejabat -->
                        <div class="mb-3">
                            <label for="foto_ttd" class="form-label fw-bold text-dark">
                                <i class="fas fa-signature me-2 text-warning"></i>
                                Tanda Tangan (Opsional)
                            </label>

                            <!-- Input file yang memicu preview saat gambar baru dipilih -->
                            <input type="file" name="foto_ttd" id="foto_ttd" class="form-control @error('foto_ttd') is-invalid @enderror"
                                accept="image/*" onchange="previewTTD(event)">

                            <!-- Tampilkan pesan error jika validasi gagal -->
                            @error('foto_ttd')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror

                            <!-- Preview gambar - akan menampilkan gambar lama, gambar dari session, atau gambar baru -->
                            <div class="mt-2" id="preview-container">
                                @if(session('temp_foto_ttd'))
                                <!-- Jika ada gambar dari session (validasi gagal), tampilkan ini -->
                                <small class="text-warning">Tanda tangan baru (belum disimpan)</small>
                                <div id="preview-wrapper-ttd">
                                    <img id="preview-gambar-ttd" src="{{ asset('storage/' . session('temp_foto_ttd')) }}" class="img-thumbnail" style="max-height: 150px;" alt="Preview TTD Baru">
                                    <!-- <div class="small text-muted">Preview tanda tangan baru</div> -->
                                </div>
                                @elseif($pejabat->foto_ttd)
                                <!-- Jika ada gambar lama dan tidak ada session, tampilkan gambar lama -->
                                <small class="text-success">Tanda tangan saat ini</small>
                                <div id="gambar-ttd-lama">
                                    <img src="{{ asset('storage/' . $pejabat->foto_ttd) }}" alt="Tanda Tangan Saat Ini" class="img-thumbnail"
                                        style="max-height: 150px;">
                                </div>
                                <!-- Container untuk preview gambar baru (hidden by default) -->
                                <div class="d-none" id="preview-wrapper-ttd">
                                    <small class="text-warning">Preview tanda tangan baru</small>
                                    <div>
                                        <img id="preview-gambar-ttd" class="img-thumbnail" style="max-height: 150px;" alt="Preview TTD Baru">
                                        <!-- <div class="small text-muted">Preview tanda tangan baru</div> -->
                                    </div>
                                </div>
                                @else
                                <!-- Jika tidak ada gambar sama sekali -->
                                <div class="d-none" id="preview-wrapper-ttd">
                                    <small class="text-warning">Preview tanda tangan baru</small>
                                    <div>
                                        <img id="preview-gambar-ttd" class="img-thumbnail" style="max-height: 150px;" alt="Preview TTD Baru">
                                        <!-- <div class="small text-muted">Preview tanda tangan baru</div> -->
                                    </div>
                                </div>
                                @endif
                            </div>
                            <!-- Container untuk tombol hapus gambar -->
                            <div id="hapus-gambar-wrapper" class="mt-1 {{ session('temp_foto_ttd') || $pejabat->foto_ttd ? '' : 'd-none' }}">
                                <small class="text-muted">File yang sudah dipilih sebelumnya</small>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="hapusGambarTemp()">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                            <!-- Info tambahan -->
                            <div class="form-text">Kosongkan jika tidak ingin mengubah. Format PNG, JPG, JPEG. Maksimal 10Mb</div>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar-plus me-2 text-warning"></i>
                                Tanggal Mulai Jabatan<span class="text-danger"> *</span>
                            </label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai', $pejabat->tanggal_mulai->format('Y-m-d')) }}" required>
                            @error('tanggal_mulai')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar-times me-2 text-warning"></i>
                                Tanggal Selesai Jabatan
                            </label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai', $pejabat->tanggal_selesai?->format('Y-m-d')) }}">
                            <div class="form-text">Kosongkan jika masih aktif</div>
                            @error('tanggal_selesai')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-warning" onclick="validasiSebelumKonfirmasi()">
                                <i class="bi bi-save-fill me-1"></i>
                                Update
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
        const form = document.getElementById('edit-form');

        // Bersihkan error lama dari validasi frontend
        document.querySelectorAll('.text-danger.dynamic-error').forEach(e => e.remove());

        const requiredFields = ['nama', 'tanggal_mulai'];
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

        // Semua valid, tampilkan konfirmasi simpan
        Swal.fire({
            title: "Simpan Perubahan Data?",
            html: "Apakah Anda yakin ingin menyimpan perubahan data pengurus ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, simpan",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>

<script>
    function previewTTD(event) {
        const input = event.target;
        const previewImg = document.getElementById('preview-gambar-ttd');
        const previewWrapper = document.getElementById('preview-wrapper-ttd');
        const gambarLama = document.getElementById('gambar-ttd-lama');
        const hapusWrapper = document.getElementById('hapus-gambar-wrapper');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewWrapper.classList.remove('d-none');
                if (gambarLama) gambarLama.classList.add('d-none');
                if (hapusWrapper) hapusWrapper.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            previewWrapper.classList.add('d-none');
            if (gambarLama) gambarLama.classList.remove('d-none');
            if (hapusWrapper) hapusWrapper.classList.add('d-none');
        }
    }
</script>


<!-- Notifikasi Error -->
@if ($errors->any())
<script>
    let errorMessages = '';


    Swal.fire({
        icon: 'error',
        title: 'Error Validasi!',
        html: errorMessages.replace(/\n/g, '<br>'),
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545',
    });
</script>
@endif

<script>
    function hapusGambarTemp() {
        // Reset input file
        document.getElementById('foto_ttd').value = '';

        // Sembunyikan preview baru
        const previewWrapper = document.getElementById('preview-wrapper-ttd');
        if (previewWrapper) {
            previewWrapper.classList.add('d-none');
        }

        // Kosongkan gambar preview (tapi jangan hapus element-nya)
        const previewImg = document.getElementById('preview-gambar-ttd');
        if (previewImg) {
            previewImg.src = '';
        }

        // Sembunyikan gambar lama (jika ada)
        const gambarLama = document.getElementById('gambar-ttd-lama');
        if (gambarLama) {
            gambarLama.classList.add('d-none');
        }

        // Hapus input hidden foto_ttd_temp dari form (jika ada)
        const inputTemp = document.querySelector('input[name="foto_ttd_temp"]');
        if (inputTemp) {
            inputTemp.remove();
        }

        // Kirim request ke server untuk hapus gambar, baik temp atau tetap
        fetch('{{ route("pejabat.hapus_gambar", $pejabat->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Gambar berhasil dihapus');
                } else {
                    console.warn('Gagal menghapus gambar:', data.message);
                }
            });
    }
</script>
<script>
    function hapusGambarTemp() {
        document.getElementById('foto_ttd').value = '';

        const previewWrapper = document.getElementById('preview-wrapper-ttd');
        const previewImg = document.getElementById('preview-gambar-ttd');
        const gambarLama = document.getElementById('gambar-ttd-lama');
        const hapusWrapper = document.getElementById('hapus-gambar-wrapper');

        if (previewWrapper) previewWrapper.classList.add('d-none');
        if (previewImg) previewImg.src = '';
        if (gambarLama) gambarLama.classList.add('d-none');
        if (hapusWrapper) hapusWrapper.classList.add('d-none');

        const inputTemp = document.querySelector('input[name="foto_ttd_temp"]');
        if (inputTemp) inputTemp.remove();

        fetch('{{ route("pejabat.hapus_gambar", $pejabat->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Gambar berhasil dihapus');
            } else {
                console.warn('Gagal menghapus gambar:', data.message);
            }
        });
    }
</script>



@endsection