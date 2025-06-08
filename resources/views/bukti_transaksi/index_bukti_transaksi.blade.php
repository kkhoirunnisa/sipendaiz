@extends('layouts.app')

@section('content')
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
                    <div class="input-group shadow rounded-3 overflow-hidden">
                        <input type="text" id="search" name="search" class="form-control form-control-sm border-0 bg-light px-3"
                            placeholder="Cari data bukti transaksi..." value="{{ request('search') }}">
                        <span class="input-group-text bg-success text-white"><i class="bi bi-search"></i></span>
                    </div>
                </div>
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
                        <td colspan="13" class="text-center">Tidak ada data bukti transaksi pemasukan</td>
                    </tr>
                    @else
                    @foreach ($buktiTransaksi as $bt)
                    <tr>
                        <td class="text-center fw-semibold text-muted">
                            <span class="badge bg-success bg-opacity-10 text-success">{{ $buktiTransaksi->firstItem() + $loop->index }}</span>
                        </td>
                        <td class="text-center">{{ $bt->donatur }}</td>
                        <td class="text-center">{{ $bt->alamat }}</td>
                        <td class="text-center">{{ $bt->nomor_telepon }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($bt->tanggal_infak)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $bt->kategori }}</td>
                        <td class="text-center">{{ $bt->metode }}</td>
                        <td class="text-center">
                            {{ $bt->nominal == 0 ? '' : number_format($bt->nominal, 0, ',', '.') }}
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
                            @if($bt->status == 'Terverifikasi')
                            <span class="badge bg-success">Terverifikasi</span>
                            @elseif($bt->status == 'Pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                            @else
                            <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $bt->user->nama ?? $bt->nama }}
                        </td>
                        <td class="text-center">
                            @if($bt->status == 'Pending')
                            <a href="{{ route('bukti_transaksi.edit', $bt->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pen-fill"></i></a>
                            <form action="{{ route('bukti_transaksi.destroy', $bt->id) }}" method="POST" class="d-inline" id="delete-form-{{ $bt->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('{{ $bt->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-muted">-</span>
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
@endsection