@extends('layouts.app')

@section('content')

<div class="px-3">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="bi bi-box2-fill fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Zakat Masuk</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Zakat</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Pemasukan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-dark mb-1 fw-bold">Data Zakat Masuk</h4>
                <p class="text-dark-50 mb-0 small">Kelola data zakat masjid</p>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                @if(auth()->user()->role == 'Bendahara')
                <a class="btn btn-primary mb-2" href="{{ route('zakat_masuk.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Zakat Masuk
                </a>
                @endif
                <div class="d-flex flex-column gap-2" style="max-width: 320px;">
                    <!-- Input Pencarian -->
                    <div class="input-group shadow rounded-3 overflow-hidden">
                        <input type="text" id="search" name="search" class="form-control form-control-sm border-0 bg-light px-3"
                            placeholder="Cari data zakat..." value="{{ request('search') }}">
                        <span class="input-group-text bg-success text-white"><i class="bi bi-search"></i></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive border shadow rounded-4">
                <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 800px;">
                    <thead class="table-gradient text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jenis Zakat</th>
                            <th class="text-center">Bentuk Zakat</th>
                            <th class="text-center">Nominal (Rp)</th>
                            <th class="text-center">Jumlah (Kg)</th>
                            <th class="text-center">Keterangan (Muzaki)</th>
                            <th class="text-center">Pengelola</th>
                            @if(auth()->user()->role == 'Bendahara')
                            <th class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="tabel-zakat">
                        @if ($zakatMasuk->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data zakat masuk</td>
                        </tr>
                        @else
                        @foreach ($zakatMasuk as $zm)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                <span class="badge bg-success bg-opacity-10 text-success">{{ $zakatMasuk->firstItem() + $loop->index }}</span>
                            </td>
                            <td class="text-center">{{ $zm->tanggal }}</td>
                            <td class="text-center">{{ $zm->jenis_zakat }}</td>
                            <td class="text-center">{{ $zm->bentuk_zakat }}</td>
                            <td class="text-center">
                                {{ $zm->nominal == 0 ? '' : number_format($zm->nominal, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                {{ $zm->jumlah == 0 ? '' : rtrim(rtrim(number_format($zm->jumlah, 2, ',', '.'), '0'), ',') }}
                            </td>
                            <td class="text-center">{!! $zm->keterangan !!}</td>
                            <td class="text-center">
                                {{ $zm->user->nama ?? $zm->nama }}
                            </td>
                            @if(auth()->user()->role == 'Bendahara')
                            <td class="text-center">
                                <a href="{{ route('zakat_masuk.edit', $zm->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pen-fill"></i></a>
                                <form id="delete-form-{{ $zm->id }}" action="{{ route('zakat_masuk.destroy', $zm->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('{{ $zm->id }}')">
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

            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap px-2">
                <div class="d-flex align-items-center">
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ $zakatMasuk->firstItem() }} - {{ $zakatMasuk->lastItem() }} of {{ $zakatMasuk->total() }} rows
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        <a href="{{ $zakatMasuk->previousPageUrl() }}" class="btn-sm text-white">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                        <span class="mx-2">Page {{ $zakatMasuk->currentPage() }} of {{ $zakatMasuk->lastPage() }}</span>
                        <a href="{{ $zakatMasuk->nextPageUrl() }}" class="btn-sm text-white">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Konfirmasi Hapus -->
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: "Hapus Data Zakat Masuk",
            html: "Apakah anda yakin akan menghapus data zakat masuk ini? <b>Zakat masuk yang dihapus tidak dapat dikembalikan lagi.</b>",
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

<!-- Notifikasi -->
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#2d7d32',
        timerProgressBar: true,
        timer: 4000,
    });
</script>
@endif

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
                        const newRows = doc.querySelector('#tabel-zakat');
                        const oldRows = document.querySelector('#tabel-zakat');
                        if (newRows && oldRows) {
                            oldRows.innerHTML = newRows.innerHTML;
                        }
                    });
            }, 300); // debounce delay
        });
    });
</script>

@endsection