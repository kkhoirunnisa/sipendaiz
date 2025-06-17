@extends('layouts.app')

@section('content')

<style>
    .modal-xl {
        max-width: 1200px;
    }

    .modal-xxl {
        max-width: 90vw;
    }

    .card {
        transition: transform 0.2s ease-in-out;
    }

    /* Hilangkan hover effect pada card di dalam modal */
    .modal .card:hover {
        transform: none;
    }

    .badge {
        font-weight: 300;
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .modal-content {
        border-radius: 1rem;
        overflow: hidden;
        max-height: 90vh;
    }

    .modal-header {
        border-bottom: none;
        padding: 1rem 1.5rem 0.5rem;
    }

    .modal-body {
        padding: 1rem 1.5rem;
        max-height: calc(90vh - 120px);
        overflow-y: auto;
    }

    .modal-footer {
        padding: 0.5rem 1.5rem 1rem;
        border-top: none;
    }

    .modal {
        --bs-backdrop-opacity: 0.5;
    }

    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }

    .table-hover tbody tr:hover {
        --bs-table-accent-bg: var(--bs-table-hover-bg);
        color: var(--bs-table-hover-color);
        transition: all 0.15s ease-in-out;
    }

    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }

    .modal .card {
        margin-bottom: 0.75rem;
    }

    .modal .card-body {
        padding: 0.75rem;
    }

    .modal .card-header {
        padding: 0.5rem 0.75rem;
    }

    /* Responsive untuk modal */
    @media (max-width: 1200px) {
        .modal-xxl {
            max-width: 95vw;
        }
    }

    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-xxl,
        .modal-xl {
            max-width: calc(100vw - 1rem);
        }

        .modal-content {
            max-height: 95vh;
        }

        .modal-body {
            max-height: calc(95vh - 100px);
            padding: 0.75rem;
        }

        .modal .card-body {
            padding: 0.5rem;
        }

        .modal .row.g-2 {
            --bs-gutter-x: 0.5rem;
            --bs-gutter-y: 0.5rem;
        }
    }
</style>

<div id="app" data-success="{{ session('success') }}"></div>
<div class="px-3">
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
                    <span class="text-success fw-semibold">Konfirmasi Transaksi</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-dark mb-1 fw-bold">Data Konfirmasi Transaksi</h4>
                <p class="text-dark-50 mb-0 small">Kelola data konfirmasi transaksi infak</p>
            </div>
            <div class="d-flex flex-column gap-2 ms-md-auto mb-4" style="max-width: 320px;">
                <!-- Form Pencarian -->
                <form method="GET" action="{{ route('bukti_transaksi.konfirmasi') }}" class="d-flex">
                    <div class="input-group shadow rounded-3 overflow-hidden">
                        <input type="text" id="search" name="search"
                            class="form-control form-control-sm border-0 bg-light px-3"
                            placeholder="Cari data bukti transaksi..." value="{{ request('search') }}">
                        <button class="input-group-text bg-success text-white border-0" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <!-- tabel -->
            <div class="table-responsive border shadow rounded-4">
                <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 800px;">
                    <thead class="table-gradient text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Donatur</th>
                            <th class="text-center">Tanggal Infak</th>
                            <th class="text-center">Metode</th>
                            <th class="text-center">Jenis Infak</th>
                            <th class="text-center">Nominal/Barang</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bukti-transaksi">
                        @if ($buktiTransaksi->isEmpty())
                        <tr>
                            <td colspan="14" class="text-center">Tidak ada transaksi pemasukan yang perlu dikonfirmasi.</td>
                        </tr>
                        @else
                        @foreach ($buktiTransaksi as $bt)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    {{ $buktiTransaksi->firstItem() + $loop->index }}
                                </span>
                            </td>
                            <td class="text-center">{{ $bt->donatur }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($bt->tanggal_infak)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $bt->metode }}</td>
                            <td class="text-center">{{ $bt->jenis_infak }}</td>
                            <td class="text-center">
                                @if($bt->jenis_infak == 'Barang')
                                {{ $bt->barang }}
                                @else
                                {{ number_format($bt->nominal, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">{{ $bt->status }}</span>
                            </td>
                            <td class="text-center">
                                <!-- Tombol Detail -->
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                    <button class="btn btn-sm btn-info text-white modal-trigger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal-{{ $bt->id }}"
                                        type="button">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </span>

                                <!-- <button class="btn btn-success btn-sm" title="Verifikasi" onclick="verifikasi('{{ $bt->id }}')">
                                    <i class="bi bi-check-circle-fill"></i>
                                </button>

                                <button class="btn btn-danger btn-sm" title="Tolak" onclick="tolak('{{ $bt->id }}')">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>

                                <form id="verifikasi-form-{{ $bt->id }}" action="{{ route('bukti_transaksi.verifikasi', $bt->id) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>

                                <form id="tolak-form-{{ $bt->id }}" action="{{ route('bukti_transaksi.tolak', $bt->id) }}" method="POST" style="display: none;">
                                    @csrf
                                </form> -->
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap px-2">
                <!-- Pagination Info -->
                <div class="d-flex align-items-center">
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        <i class="bi bi-info-circle me-1"></i>
                        Menampilkan {{ $buktiTransaksi->firstItem() }} - {{ $buktiTransaksi->lastItem() }} dari {{ $buktiTransaksi->total() }} data
                    </div>
                </div>

                <!-- Pagination Navigation -->
                <div class="d-flex align-items-center">
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        {{-- Previous button --}}
                        @if ($buktiTransaksi->onFirstPage())
                        <span class="btn-sm text-white opacity-50">
                            <i class="bi bi-chevron-left"></i> Previous
                        </span>
                        @else
                        <a href="{{ $buktiTransaksi->previousPageUrl() }}" class="btn-sm text-white">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                        @endif

                        <span class="mx-2">Page {{ $buktiTransaksi->currentPage() }} of {{ $buktiTransaksi->lastPage() }}</span>

                        {{-- Next button --}}
                        @if ($buktiTransaksi->currentPage() == $buktiTransaksi->lastPage())
                        <span class="btn-sm text-white opacity-50">
                            Next <i class="bi bi-chevron-right"></i>
                        </span>
                        @else
                        <a href="{{ $buktiTransaksi->nextPageUrl() }}" class="btn-sm text-white">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Detail untuk setiap bukti transaksi -->
@if (!$buktiTransaksi->isEmpty())
@foreach ($buktiTransaksi as $bt)
<div class="modal fade mt-2" id="detailModal-{{ $bt->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $bt->id }}" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xxl modal-dialog-centered">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-success text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-receipt me-2"></i>
                    <div>
                        <h5 class="modal-title mb-0" id="detailModalLabel-{{ $bt->id }}">Detail Bukti Transaksi - Konfirmasi</h5>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <!-- Status Banner -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span class="fw-semibold">Status Verifikasi</span>
                            <div>
                                @if($bt->status == 'Terverifikasi')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                </span>
                                @elseif($bt->status == 'Pending')
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                                @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Ditolak
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content dengan layout 2 kolom untuk desktop -->
                <div class="row g-3">
                    <!-- Kolom Kiri -->
                    <div class="col-lg-6">
                        <!-- Informasi Donatur -->
                        <div class="card border h-100">
                            <div class="card-header bg-success text-white py-2">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-user me-2"></i>Informasi Donatur
                                </h6>
                            </div>
                            <div class="card-body py-2">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small mb-1 fw-semibold">Nama Donatur</label>
                                        <p class="mb-2">{{ $bt->donatur }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small mb-1 fw-semibold">Alamat</label>
                                        <p class="mb-2">{{ $bt->alamat ?: '-' }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small mb-1 fw-semibold">Nomor Telepon</label>
                                        <p class="mb-2">{{ $bt->nomor_telepon ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-lg-6">
                        <!-- Detail Transaksi -->
                        <div class="card border h-100">
                            <div class="card-header bg-success text-white py-2">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-receipt me-2"></i>Detail Transaksi
                                </h6>
                            </div>
                            <div class="card-body py-2">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Tanggal Infak</label>
                                        <p class="mb-2">
                                            {{ \Carbon\Carbon::parse($bt->tanggal_infak)->format('d F Y') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Kategori</label>
                                        <p class="mb-2">
                                            <span class="badge bg-secondary">{{ $bt->kategori }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Metode</label>
                                        <p class="mb-2">
                                            <span class="badge bg-secondary">{{ $bt->metode }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Jenis Infak</label>
                                        <p class="mb-2">
                                            <span class="badge bg-secondary">{{ $bt->jenis_infak ?? 'Tidak Diketahui' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Nominal (Rp)</label>
                                        <p class="fw-bold text-success mb-2">
                                            @if($bt->nominal == 0)
                                            <span class="text-muted">-</span>
                                            @else
                                            Rp {{ number_format($bt->nominal, 0, ',', '.') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Barang</label>
                                        <p class="mb-0">
                                            {{ $bt->barang ?: '-' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Keterangan</label>
                                        <p class="mb-0">
                                            {{ $bt->keterangan ?: '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Transaksi dan Pengelola - Full Width -->
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-header bg-success text-white py-2">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-file-alt me-2"></i>Dokumentasi & Pengelola
                                </h6>
                            </div>
                            <div class="card-body py-2">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Bukti Transaksi</label>
                                        <div>
                                            @if ($bt->bukti_transaksi)
                                            <a href="{{ asset('storage/' . $bt->bukti_transaksi) }}" target="_blank"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-external-link-alt me-1"></i>Lihat Bukti
                                            </a>
                                            @else
                                            <span class="text-muted small">
                                                <i class="fas fa-times-circle me-1"></i>Tidak ada bukti
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Dikelola Oleh</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user text-primary me-2"></i>
                                            <span class="">{{ $bt->user->nama ?? $bt->nama }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer dengan tombol aksi -->
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Terakhir diperbarui: {{ $bt->updated_at ? $bt->updated_at->format('d F Y') : 'Tidak diketahui' }}
                    </small>
                    <div class="d-flex gap-2">
                        @if($bt->status == 'Pending')
                        <button type="button" class="btn btn-success btn-sm" onclick="verifikasi('{{ $bt->id }}')">
                            <i class="bi bi-check-circle-fill me-1"></i>Verifikasi
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="tolak('{{ $bt->id }}')">
                            <i class="bi bi-x-circle-fill me-1"></i>Tolak
                        </button>
                        @endif
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hidden Forms -->
            <form id="verifikasi-form-{{ $bt->id }}" action="{{ route('bukti_transaksi.verifikasi', $bt->id) }}" method="POST" style="display: none;">
                @csrf
            </form>
            <form id="tolak-form-{{ $bt->id }}" action="{{ route('bukti_transaksi.tolak', $bt->id) }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
</div>
@endforeach
@endif

<script>
    function verifikasi(id) {
        Swal.fire({
            title: "Konfirmasi Verifikasi",
            text: "Apakah Anda yakin ingin memverifikasi bukti transaksi ini?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, verifikasi",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('verifikasi-form-' + id).submit();
            }
        });
    }

    function tolak(id) {
        Swal.fire({
            title: "Tolak Transaksi",
            text: "Apakah Anda yakin ingin menolak bukti transaksi ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, tolak",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('tolak-form-' + id).submit();
            }
        });
    }

    // Script untuk mencegah multiple modal trigger
    document.addEventListener('DOMContentLoaded', function() {
        const modalTriggers = document.querySelectorAll('.modal-trigger');
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const targetModal = this.getAttribute('data-bs-target');
                const modal = new bootstrap.Modal(document.querySelector(targetModal));
                modal.show();
            });
        });
    });
</script>

<!-- Live Search Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        let timeout = null;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                const keyword = searchInput.value;

                fetch(`?search=${encodeURIComponent(keyword)}`)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newRows = doc.querySelector('#bukti-transaksi');
                        const oldRows = document.querySelector('#bukti-transaksi');
                        if (newRows && oldRows) {
                            oldRows.innerHTML = newRows.innerHTML;
                        }
                    });
            }, 300); // debounce delay
        });
    });
</script>

<!-- latar belakang modal agar terhapus -->
<script>
    document.addEventListener('hidden.bs.modal', function() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open'); // menghapus kelas yang mencegah scroll
        document.body.style = ''; // reset style body
    });
</script>

<!-- Notifikasi Sukses -->
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#2d7d32',
        timer: 4000,
        timerProgressBar: true,
    });
</script>
@endif

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