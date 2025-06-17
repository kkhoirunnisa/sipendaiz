@extends('layouts.app')

@section('content')
<div id="app" data-success="{{ session('success') }}"></div>
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
                <h3 class="mb-0 fw-bold text-dark">User</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Users</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">User</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-dark mb-1 fw-bold">Data User</h4>
                <p class="text-dark-50 mb-0 small">Kelola data user / petugas masjid</p>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <!-- Tombol Tambah -->
                <a class="btn btn-primary mb-2" href="{{ route('user.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah User
                </a>
                <div class="d-flex flex-column gap-2" style="max-width: 320px;">
                    <!-- Form Pencarian -->
                    <form method="GET" action="{{ route('user.index') }}" class="d-flex">
                        <div class="input-group shadow rounded-3 overflow-hidden">
                            <input type="text" id="search" name="search"
                                class="form-control form-control-sm border-0 bg-light px-3"
                                placeholder="Cari data user..." value="{{ request('search') }}">
                            <button class="input-group-text bg-success text-white border-0" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- tabel -->
            <div class="table-responsive border shadow rounded-4">
                <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 800px;">
                    <thead class="table-gradient text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Nomor Telepon</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Username</th>
                            <!-- <th class="text-center">Password</th> -->
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="user">
                        @if ($user->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data user</td>
                        </tr>
                        @else
                        @foreach ($user as $p)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    {{ $user->firstItem() + $loop->index }}</span>
                            </td>
                            <td class="text-center">{{ $p->nama }}</td>
                            <td class="text-center">{{ $p->nomor_telepon }}</td>
                            <td class="text-center">{{ $p->role }}</td>
                            <td class="text-center">{{ $p->username }}</td>
                            <td class="text-center">
                                <a href="{{ route('user.edit', $p->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pen-fill"></i>
                                </a>
                                <form id="delete-form-{{ $p->id }}" action="{{ route('user.destroy', $p->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('{{ $p->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
                        Menampilkan {{ $user->firstItem() }} - {{ $user->lastItem() }} of {{ $user->total() }} rows
                    </div>
                </div>

                <!-- Pagination Navigation -->
                <div class="d-flex align-items-center">
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        {{-- Previous button --}}
                        @if ($user->onFirstPage())
                        <span class="btn-sm text-white opacity-50">
                            <i class="bi bi-chevron-left"></i> Previous
                        </span>
                        @else
                        <a href="{{ $user->previousPageUrl() }}" class="btn-sm text-white">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                        @endif

                        <span class="mx-2">Page {{ $user->currentPage() }} of {{ $user->lastPage() }}</span>

                        {{-- Next button --}}
                        @if ($user->currentPage() == $user->lastPage())
                        <span class="btn-sm text-white opacity-50">
                            Next <i class="bi bi-chevron-right"></i>
                        </span>
                        @else
                        <a href="{{ $user->nextPageUrl() }}" class="btn-sm text-white">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: "Hapus User",
            html: "Apakah anda yakin akan menghapus User ini? <b>User yang dihapus tidak dapat dikembalikan lagi.</b>",
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
                        const newRows = doc.querySelector('#user');
                        const oldRows = document.querySelector('#user');
                        if (newRows && oldRows) {
                            oldRows.innerHTML = newRows.innerHTML;
                        }
                    });
            }, 300); // debounce delay
        });
    });
</script>
@endsection