@extends('layouts.app')

@section('content')

<div class="px-3">
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
                <div class="align-items-center d-none d-md-flex">
                    <span class="text-muted">Infak </span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-muted">{{ ucfirst($kategori) }} </span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Pengeluaran </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-dark mb-1 fw-bold">Data Infak Keluar</h4>
                <p class="text-dark-50 mb-0 small">Kelola data infak masjid</p>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                @if(auth()->user()->role == 'Bendahara')
                <!-- Tombol Tambah -->
                <a class="btn btn-primary d-flex align-items-center gap-2" href="{{ route('infak_keluar.create', ['kategori' => $kategori]) }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Infak Keluar
                </a>
                @endif
                <div class="d-flex flex-column gap-2" style="max-width: 320px;">
                    <!-- Form Pencarian -->
                    <div class="input-group shadow rounded-3 overflow-hidden">
                        <input type="text" id="search" name="search" class="form-control form-control-sm border-0 bg-light px-3"
                            placeholder="Cari data infak..." value="{{ request('search') }}">
                        <span class="input-group-text bg-success text-white"><i class="bi bi-search"></i></span>
                    </div>
                </div>
            </div>

            <!-- tabel -->
            <div class="table-responsive border shadow rounded-4">
                <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 800px;">
                    <thead class="table-gradient text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Nominal (Rp)</th>
                            <th class="text-center">Barang</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Bukti</th>
                            <th class="text-center">Pengelola</th>
                            @if(auth()->user()->role == 'Bendahara')
                            <th class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="infak">
                        @if ($infakKeluar->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data infak keluar</td>
                        </tr>
                        @else
                        @foreach ($infakKeluar as $ik)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    {{ $infakKeluar->firstItem() + $loop->index }}
                                </span>
                            </td>
                            <td class="text-center">{{ $ik->tanggal }}</td>
                            <td class="text-center">{{ ucfirst($ik->kategori) }}</td>
                            <td class="text-center">{{ number_format($ik->nominal, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $ik->barang }}</td>
                            <td class="text-center">{{ $ik->keterangan }}</td>
                            <td class="text-center">
                                @if($ik->bukti_infak_keluar)
                                <a href="{{ asset('storage/' . $ik->bukti_infak_keluar) }}" target="_blank"
                                    style="color: black; text-decoration: none;" title="Lihat Bukti">
                                    <i class="bi bi-file-earmark-fill" style="font-size: 1.2rem;"></i>
                                </a>
                                @else
                                <i>Tidak ada</i>
                                @endif
                            </td>
                             <td class="text-center">
                                {{ $ik->user->nama ?? $ik->nama }}
                            </td>
                            @if(auth()->user()->role == 'Bendahara')
                            <td class="text-center">
                                <a href="{{ route('infak_keluar.edit', $ik->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pen-fill"></i>
                                </a>
                                <form id="delete-form-{{ $ik->id }}" action="{{ route('infak_keluar.destroy', $ik->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('{{ $ik->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
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
                        Menampilkan {{ $infakKeluar->firstItem() }} - {{ $infakKeluar->lastItem() }} dari {{ $infakKeluar->total() }} data
                    </div>
                </div>

                <!-- Pagination Navigation -->
                <div class="d-flex align-items-center">
                    <!-- Previous and Next buttons -->
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        <a href="{{ $infakKeluar->previousPageUrl() }}" class="btn-sm text-white">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                        <span class="mx-2">Page {{ $infakKeluar->currentPage() }} of {{ $infakKeluar->lastPage() }}</span>
                        <a href="{{ $infakKeluar->nextPageUrl() }}" class="btn-sm text-white">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: "Hapus Infak Keluar",
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
@endsection