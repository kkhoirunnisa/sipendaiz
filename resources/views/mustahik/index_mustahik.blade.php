@extends('layouts.app')

@section('content')
<div class="px-3">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Mustahik</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Zakat</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Mustahik</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-dark mb-1 fw-bold">Data Mustahik</h4>
                <p class="text-dark-50 mb-0 small">Kelola data penerima zakat</p>
            </div>
            <div class="d-flex justify-content-between align-items-start flex-wrap mb-3 gap-2">
                <!-- Tombol Tambah Mustahik -->
                <a class="btn btn-primary d-flex align-items-center gap-2" href="{{ route('mustahik.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Mustahik
                </a>

                <!-- Form Pencarian dan Tombol PDF -->
                <div class="d-flex flex-column gap-2" style="max-width: 320px;">
                    <!-- Form Pencarian -->
                     <!-- Input Pencarian -->
                    <div class="input-group shadow rounded-3 overflow-hidden">
                        <input type="text" id="search" name="search" class="form-control form-control-sm border-0 bg-light px-3"
                            placeholder="Cari data mustahik..." value="{{ request('search') }}">
                        <span class="input-group-text bg-success text-white"><i class="bi bi-search"></i></span>
                    </div>

                    <!-- Tombol PDF -->
                    <a class="btn btn-outline-danger btn-sm shadow-sm d-flex align-items-center justify-content-center gap-2 w-100"
                        href="{{ route('mustahik.pdf', ['search' => request('search')]) }}"
                        target="_blank" title="Unduh Data Mustahik">
                        <i class="bi bi-file-earmark-arrow-down-fill"></i>
                        <span>Unduh PDF</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- tabel -->
        <div class="table-responsive border shadow rounded-4">
            <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 800px;">
                <thead class="table-gradient text-white">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center">Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="mustahik">
                    @forelse ($mustahik as $m)
                    <tr>
                        <td class="text-center fw-semibold text-muted">
                            <span class="badge bg-success bg-opacity-10 text-success">
                                {{ $mustahik->firstItem() + $loop->index }}
                            </span>
                        </td>
                        <td class="text-center">{{ $m->nama }}</td>
                        <td class="text-center">{{ $m->kategori }}</td>
                        <td class="text-center">{{ $m->alamat }}</td>
                        <td class="text-center">
                            <a href="{{ route('mustahik.edit', $m->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pen-fill"></i></a>
                            <form id="delete-form-{{ $m->id }}" action="{{ route('mustahik.destroy', $m->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('{{ $m->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data mustahik</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap px-2">
            <!-- Pagination Info -->
            <div class="d-flex align-items-center">
                <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                    <i class="bi bi-info-circle me-1"></i>
                    Menampilkan {{ $mustahik->firstItem() }} - {{ $mustahik->lastItem() }} dari {{ $mustahik->total() }} data
                </div>
            </div>

            <!-- Pagination Navigation -->
            <div class="d-flex align-items-center">
                <!-- Previous and Next buttons -->
                <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                    <a href="{{ $mustahik->previousPageUrl() }}" class="btn-sm text-white">
                        <i class="bi bi-chevron-left"></i> Previous
                    </a>
                    <span class="mx-2">Page {{ $mustahik->currentPage() }} of {{ $mustahik->lastPage() }}</span>
                    <a href="{{ $mustahik->nextPageUrl() }}" class="btn-sm text-white">
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
            title: "Hapus Mustahik",
            html: "Apakah anda yakin akan menghapus Mustahik ini? <b>Mustahik yang dihapus tidak dapat dikembalikan lagi.</b>",
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
                            const newRows = doc.querySelector('#mustahik');
                            const oldRows = document.querySelector('#mustahik');
                            if (newRows && oldRows) {
                                oldRows.innerHTML = newRows.innerHTML;
                            }
                        });
                }, 300); // debounce delay
            });
        });
    </script>
@endsection