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

<div class="px-3">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    {{-- Cek kategori untuk menentukan ikon --}}
                    @if ($kategori == 'Pembangunan')
                    <i class="fas fa-mosque fs-4"></i>
                    @elseif ($kategori == 'Takmir')
                    <i class="fas fa-people-group fs-4"></i>
                    @else
                    <i class="fas fa-donate fs-4"></i> {{-- default icon jika kategori tidak dikenali --}}
                    @endif
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Infak Masuk {{ ucfirst($kategori) }} Masjid</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Infak </span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">{{ ucfirst($kategori) }} </span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Pemasukan </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-dark mb-1 fw-bold">Data Infak Masuk</h4>
                <p class="text-dark-50 mb-0 small">Kelola data infak masjid</p>
            </div>

            <div class="d-flex flex-column gap-2 ms-md-auto mb-4" style="max-width: 320px;">
                <!-- Form Pencarian -->
                <form method="GET" action="{{ route('infak_masuk.index', ['kategori' => request('kategori') ?? 'Pembangunan']) }}" class="d-flex">
                    <div class="input-group shadow rounded-3 overflow-hidden">
                        <input type="text" id="search" name="search"
                            class="form-control form-control-sm border-0 bg-light px-3"
                            placeholder="Cari data infak..." value="{{ request('search') }}">
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
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Metode</th>
                            <th class="text-center">Nominal (Rp)/Barang</th>
                            <th class="text-center">Tanggal Konfirmasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="infak">
                        @if($infakMasuk->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data infak masuk</td>
                        </tr>
                        @else
                        @foreach ($infakMasuk as $im)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    {{ $infakMasuk->firstItem() + $loop->index }}
                                </span>
                            </td>
                            <td class="text-center">{{ $im->buktiTransaksi->donatur }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($im->buktiTransaksi->tanggal_infak)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $im->buktiTransaksi->kategori }}</td>
                            <td class="text-center">{{ $im->buktiTransaksi->metode }}</td>
                            <td class="text-center">
                                @if($im->buktiTransaksi->jenis_infak == 'Barang')
                                {{ $im->buktiTransaksi->barang }}
                                @else
                                @if($im->buktiTransaksi->nominal == 0)
                                -
                                @else
                                {{ number_format($im->buktiTransaksi->nominal, 0, ',', '.') }}
                                @endif
                                @endif
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($im->tanggal_konfirmasi)->format('d-m-Y') }}
                            </td>
                            <td class="text-center">
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                    <button class="btn btn-sm btn-info text-white modal-trigger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal-{{ $im->id }}"
                                        type="button">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </span>
                                <!-- @if(in_array(auth()->user()->role, ['Bendahara', 'Petugas']))
                                <a href="{{ route('infak.kuitansi.pdf', $im->id) }}"
                                    class="btn btn-sm btn-danger"
                                    title="Lihat Kuitansi PDF"
                                    target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </a>
                                @endif -->
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
                        Menampilkan {{ $infakMasuk->firstItem() }} - {{ $infakMasuk->lastItem() }} dari {{ $infakMasuk->total() }} data
                    </div>
                </div>

                <!-- Pagination Navigation -->
                <div class="d-flex align-items-center">
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        {{-- Previous button --}}
                        @if ($infakMasuk->onFirstPage())
                        <span class="btn-sm text-white opacity-50">
                            <i class="bi bi-chevron-left"></i> Previous
                        </span>
                        @else
                        <a href="{{ $infakMasuk->previousPageUrl() }}" class="btn-sm text-white">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                        @endif

                        <span class="mx-2">Page {{ $infakMasuk->currentPage() }} of {{ $infakMasuk->lastPage() }}</span>

                        {{-- Next button --}}
                        @if ($infakMasuk->currentPage() == $infakMasuk->lastPage())
                        <span class="btn-sm text-white opacity-50">
                            Next <i class="bi bi-chevron-right"></i>
                        </span>
                        @else
                        <a href="{{ $infakMasuk->nextPageUrl() }}" class="btn-sm text-white">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail untuk setiap infak masuk -->
@if (!$infakMasuk->isEmpty())
@foreach ($infakMasuk as $im)
<div class="modal fade mt-2" id="detailModal-{{ $im->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $im->id }}" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xxl modal-dialog-centered">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-success text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-donate me-2"></i>
                    <div>
                        <h5 class="modal-title mb-0" id="detailModalLabel-{{ $im->id }}">Detail Infak Masuk</h5>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <!-- Status Banner -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                            <span class="fw-semibold">Status Infak</span>
                            <div>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                </span>
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
                                        <p class="mb-2">{{ $im->buktiTransaksi->donatur }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small mb-1 fw-semibold">Alamat</label>
                                        <p class="mb-2">{{ $im->buktiTransaksi->alamat ?: '-' }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small mb-1 fw-semibold">Nomor Telepon</label>
                                        <p class="mb-2">{{ $im->buktiTransaksi->nomor_telepon ?: '-' }}</p>
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
                                            {{ \Carbon\Carbon::parse($im->buktiTransaksi->tanggal_infak)->format('d F Y') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Tanggal Konfirmasi</label>
                                        <p class="mb-2">
                                            {{ \Carbon\Carbon::parse($im->tanggal_konfirmasi)->format('d F Y') }}
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Kategori</label>
                                        <p class="mb-2">
                                            <span class="badge bg-secondary">{{ $im->buktiTransaksi->kategori }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Metode</label>
                                        <p class="mb-2">
                                            <span class="badge bg-secondary">{{ $im->buktiTransaksi->metode }}</span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Jenis Infak</label>
                                        <p class="mb-2">
                                            <span class="badge bg-secondary">{{ $im->buktiTransaksi->jenis_infak }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Nominal</label>
                                        <p class="fw-bold text-success mb-2">
                                            @if($im->buktiTransaksi->nominal == 0)
                                            <span class="text-muted">-</span>
                                            @else
                                            Rp {{ number_format($im->buktiTransaksi->nominal, 0, ',', '.') }}
                                            @endif
                                        </p>
                                    </div>

                                    {{-- Barang dan Keterangan dalam satu baris --}}
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Barang</label>
                                        <p class="mb-0">
                                            {{ $im->buktiTransaksi->barang ?: '-' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small mb-1 fw-semibold">Keterangan</label>
                                        <p class="mb-0">
                                            {{ $im->buktiTransaksi->keterangan ?: '-' }}
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
                                    <div class="col-md-4">
                                        <label class="form-label small mb-1 fw-semibold">Bukti Transaksi</label>
                                        <div>
                                            @if ($im->buktiTransaksi->bukti_transaksi)
                                            <a href="{{ asset('storage/' . $im->buktiTransaksi->bukti_transaksi) }}" target="_blank"
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
                                    <div class="col-md-4">
                                        <label class="form-label small mb-1 fw-semibold">Dikelola Oleh</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user text-primary me-2"></i>
                                            <span class="">{{ $im->buktiTransaksi->user->nama ?? $im->buktiTransaksi->nama }}</span>
                                        </div>
                                    </div>

                                    <!-- Kuitansi hanya muncul jika kategori bukan Takmir -->
                                    @if($im->buktiTransaksi->kategori !== 'Takmir' && in_array(auth()->user()->role, ['Bendahara', 'Petugas']))
                                    <div class="col-md-4">
                                        <label class="form-label small mb-1 fw-semibold">Kuitansi</label>
                                        <div>
                                            <a href="{{ route('infak.kuitansi.pdf', $im->id) }}"
                                                class="btn btn-outline-danger btn-sm"
                                                target="_blank">
                                                <i class="fas fa-file-pdf me-1"></i>Unduh Kuitansi
                                            </a>
                                        </div>
                                    </div>
                                    @endif

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
                        Terakhir diperbarui: {{ $im->updated_at ? $im->updated_at->format('d F Y') : 'Tidak diketahui' }}
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

    // Script untuk membersihkan modal backdrop
    document.addEventListener('hidden.bs.modal', function() {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        document.body.classList.remove('modal-open');
        document.body.style = '';
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
                        const newRows = doc.querySelector('#infak');
                        const oldRows = document.querySelector('#infak');
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
@endsection