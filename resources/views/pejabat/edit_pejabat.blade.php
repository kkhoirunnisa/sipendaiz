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
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $pejabat->nama) }}" required>
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

                            <!-- Info tambahan -->
                            <div class="form-text">Kosongkan jika tidak ingin mengubah. Format PNG, JPG, JPEG. Maksimal 2MB</div>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar-plus me-2 text-warning"></i>
                                Tanggal Mulai Jabatan<span class="text-danger"> *</span>
                            </label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', $pejabat->tanggal_mulai->format('Y-m-d')) }}" required>
                            @error('tanggal_mulai')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label fw-bold text-dark">
                                <i class="fas fa-calendar-times me-2 text-warning"></i>
                                Tanggal Selesai Jabatan
                            </label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $pejabat->tanggal_selesai?->format('Y-m-d')) }}">
                            <div class="form-text">Kosongkan jika masih aktif</div>
                            @error('tanggal_selesai')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-warning" onclick="konfirmasiEdit()">
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
    function konfirmasiEdit() {
        Swal.fire({
            title: "Edit Perubahan?",
            html: "Apakah Anda yakin ingin mengedit perubahan pejabat masjid ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, edit",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("edit-form").submit();
            }
        });
    }

    function previewTTD(event) {
        const input = event.target;
        const previewImg = document.getElementById('preview-gambar-ttd');
        const previewWrapper = document.getElementById('preview-wrapper-ttd');
        const gambarLama = document.getElementById('gambar-ttd-lama');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Set gambar preview
                previewImg.src = e.target.result;

                // Tampilkan preview gambar baru
                previewWrapper.classList.remove('d-none');

                // Sembunyikan gambar lama jika ada
                if (gambarLama) {
                    gambarLama.classList.add('d-none');
                }
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            // Jika tidak ada file yang dipilih, kembalikan ke kondisi semula
            previewWrapper.classList.add('d-none');
            if (gambarLama) {
                gambarLama.classList.remove('d-none');
            }
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

@endsection