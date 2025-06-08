@extends('layouts.app')

@section('content')
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
                <div class="input-group shadow rounded-3 overflow-hidden">
                    <input type="text" id="search" name="search" class="form-control form-control-sm border-0 bg-light px-3"
                        placeholder="Cari data konfirmasi transaksi..." value="{{ request('search') }}">
                    <span class="input-group-text bg-success text-white"><i class="bi bi-search"></i></span>
                </div>
            </div>
            <!-- tabel -->
            <div class="table-responsive border shadow rounded-4">
                <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 800px;">
                    <thead class="table-gradient text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Donatur</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Nomor HP</th>
                            <th class="text-center">Tanggal Infak</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Metode</th>
                            <th class="text-center">Nominal</th>
                            <th class="text-center">Barang</th>
                            <th class="text-center">Bukti</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Pengelola</th>
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
                            <td class="text-center">{{ $bt->alamat }}</td>
                            <td class="text-center">{{ $bt->nomor_telepon }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($bt->tanggal_infak)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $bt->kategori }}</td>
                            <td class="text-center">{{ $bt->metode }}</td>
                            <td class="text-center">
                                @if($bt->nominal == 0)
                                <!-- 0 Kg -->
                                @else
                                {{ number_format($bt->nominal, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="text-center">{{ $bt->barang }}</td>
                            <td class="text-center">
                                @if($bt->bukti_transaksi)
                                <a href="{{ asset('storage/' . $bt->bukti_transaksi) }}" target="_blank"
                                    style="color: black; text-decoration: none;" title="Lihat Bukti">
                                    <i class="bi bi-file-earmark-fill" style="font-size: 1.2rem;"></i>
                                </a>
                                @else
                                <i class="text-muted">Tidak ada</i>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning text-dark">{{ $bt->status }}</span>
                            </td>
                            <td class="text-center">
                                {{ $bt->user->nama ?? $bt->nama }}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-success btn-sm" title="Verifikasi" onclick="verifikasi('{{ $bt->id }}')">
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
                                </form>
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
                    <!-- Previous and Next buttons -->
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        <a href="{{ $buktiTransaksi->previousPageUrl() }}" class="btn-sm text-white">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                        <span class="mx-2">Page {{ $buktiTransaksi->currentPage() }} of {{ $buktiTransaksi->lastPage() }}</span>
                        <a href="{{ $buktiTransaksi->nextPageUrl() }}" class="btn-sm text-white">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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