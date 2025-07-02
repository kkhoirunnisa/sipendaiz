@extends('layouts.app')

@section('content')


<style>
    .modal-xl {
        max-width: 1200px;
        /* Diperbesar dari 1000px */
    }

    .modal-xxl {
        max-width: 90vw;
        /* Menggunakan 90% dari viewport width */
    }

    .card {
        transition: transform 0.2s ease-in-out;
    }

    /* Hilangkan hover effect pada card di dalam modal */
    .modal .card:hover {
        transform: none;
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
        /* Batasi tinggi modal */
    }

    .modal-header {
        border-bottom: none;
        padding: 1rem 1.5rem 0.5rem;
        /* Kurangi padding */
    }

    .modal-body {
        padding: 1rem 1.5rem;
        /* Kurangi padding */
        max-height: calc(90vh - 120px);
        /* Batasi tinggi body */
        overflow-y: auto;
        /* Scroll jika diperlukan */
    }

    .modal-footer {
        padding: 0.5rem 1.5rem 1rem;
        /* Kurangi padding */
        border-top: none;
    }

    /* Perbaikan untuk mencegah kedap-kedip */
    .modal {
        --bs-backdrop-opacity: 0.5;
    }

    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }

    /* Hilangkan hover effect pada tabel saat modal aktif */
    .table-hover tbody tr:hover {
        --bs-table-accent-bg: var(--bs-table-hover-bg);
        color: var(--bs-table-hover-color);
        transition: all 0.15s ease-in-out;
    }

    /* Stabilkan modal dialog */
    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }

    /* Perbaikan untuk card di dalam modal */
    .modal .card {
        margin-bottom: 0.75rem;
        /* Kurangi margin */
    }

    .modal .card-body {
        padding: 0.75rem;
        /* Kurangi padding card body */
    }

    .modal .card-header {
        padding: 0.5rem 0.75rem;
        /* Kurangi padding header */
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

<div class="px-3">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm"
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
                    <span class="text-success fw-semibold">Bukti Transaksi</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-dark mb-1 fw-bold">Data Bukti Transaksi</h4>
                <p class="text-dark-50 mb-0 small">Kelola data bukti transaksi pemasukan infak</p>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <!-- Tombol Tambah Mustahik -->
                <a class="btn btn-primary d-flex align-items-center gap-2" href="{{ route('bukti_transaksi.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Bukti Transaksi
                </a>

                <!-- Form Pencarian dan Tombol PDF -->
                <div class="d-flex flex-column gap-2" style="max-width: 320px;">
                    <!-- Form Pencarian -->
                    <form method="GET" action="{{ route('bukti_transaksi.index') }}" class="d-flex">
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
            </div>
        </div>

        <!-- tabel -->
        <div class="table-responsive border shadow rounded-4">
            <table class="table table-bordered align-items-center mb-0" style="min-width: 800px;">
                <thead class="table-gradient text-white">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kategori</th>
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
                        <td colspan="13" class="text-center">Tidak ada data bukti transaksi pemasukan</td>
                    </tr>
                    @else
                    @foreach ($buktiTransaksi as $bt)
                    <tr>
                        <td class="text-center fw-semibold text-muted">
                            <span class="badge bg-success bg-opacity-10 text-success">{{ $buktiTransaksi->firstItem() + $loop->index }}</span>
                        </td>
                        <td class="text-center">{{ $bt->kategori }}</td>
                        <td class="text-center">{{ $bt->donatur }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($bt->tanggal_infak)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $bt->metode }}</td>
                        <td class="text-center">{{ $bt->jenis_infak }}</td>
                        <td class="text-center">
                            @if($bt->jenis_infak == 'Barang')
                            {{ $bt->barang }}
                            @else
                            {{ number_format($bt->nominal, 2, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if($bt->status == 'Terverifikasi')
                            <span class="badge bg-success">Terverifikasi</span>
                            @elseif($bt->status == 'Pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                            @else
                            <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                <button class="btn btn-sm btn-info text-white modal-trigger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailModal-{{ $bt->id }}"
                                    type="button">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </span>
                            @if($bt->status == 'Pending')
                            <!-- Tombol Detail -->
                            <a href="{{ route('bukti_transaksi.edit', $bt->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pen-fill"></i></a>
                            <form action="{{ route('bukti_transaksi.destroy', $bt->id) }}" method="POST" class="d-inline" id="delete-form-{{ $bt->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('{{ $bt->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-muted"></span>
                            @endif
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

<!-- Modal dipindahkan ke luar loop dan dibuat sekali saja -->
@if (!$buktiTransaksi->isEmpty())
@foreach ($buktiTransaksi as $bt)
<!-- Modal Detail (Gunakan modal-xxl dan hapus modal-dialog-scrollable) -->
<div class="modal fade mt-2" id="detailModal-{{ $bt->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $bt->id }}" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xxl modal-dialog-centered"> <!-- Ganti dari modal-lg ke modal-xxl dan hapus modal-dialog-scrollable -->
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-success text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-receipt me-2"></i>
                    <div>
                        <h5 class="modal-title mb-0" id="detailModalLabel-{{ $bt->id }}">Detail Bukti Transaksi</h5>
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
                                        <p class=" mb-2">{{ $bt->donatur }}</p>
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
                                            <span class="badge bg-secondary">{{ $bt->jenis_infak }}</span>
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

                                    {{-- Barang dan Keterangan dalam satu baris --}}
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
                                            <span class="">
                                                {!! $search
                                                ? str_ireplace($search, '<span class="bg-warning text-dark">'.$search.'</span>', $bt->user->nama ?? $bt->nama)
                                                : ($bt->user->nama ?? $bt->nama)
                                                !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Terakhir diperbarui: {{ $bt->updated_at ? $bt->updated_at->format('d F Y') : 'Tidak diketahui' }}
                    </small>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: "Hapus Bukti Transaksi Pemasukan",
            html: "Apakah anda yakin akan menghapus data ini? <b>Data yang dihapus tidak dapat dikembalikan lagi.</b>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Tidak, kembali",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form-" + id).submit();
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

<!-- Live Search Script -->
<!-- <script>
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
            }, 300); // delay
        });
    });
</script> -->

<!-- latar belakang modal agar terhapus -->
<script>
    document.addEventListener('hidden.bs.modal', function() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open'); // menghapus kelas yang mencegah scroll
        document.body.style = ''; // reset style body
    });
</script>
@endsection