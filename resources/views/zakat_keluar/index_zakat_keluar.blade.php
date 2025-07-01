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
                <h3 class="mb-0 fw-bold text-dark">Zakat Keluar</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Zakat</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Pengeluaran</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-dark mb-1 fw-bold">Data Zakat Keluar</h4>
                <p class="text-dark-50 mb-0 small">Kelola data zakat masjid</p>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                @if(auth()->user()->role == 'Bendahara')
                <a class="btn btn-primary mb-2" href="{{ route('zakat_keluar.create') }}">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Zakat Keluar
                </a>
                @endif
                <div class="d-flex flex-column gap-2 ms-md-auto mb-2" style="max-width: 100%; min-width: 335px;">
                    <!-- Form Pencarian -->
                    <form method="GET" action="{{ route('zakat_keluar.index') }}" class="d-flex">
                        <div class="input-group shadow rounded-3 overflow-hidden">
                            <input type="text" id="search" name="search"
                                class="form-control form-control-sm border-0 bg-light px-3"
                                placeholder="Cari data zakat..." value="{{ request('search') }}">
                            <button class="input-group-text bg-success text-white border-0" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive border shadow rounded-4">
                <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 800px;">
                    <thead class="table-gradient text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Mustahik</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jenis Zakat</th>
                            <th class="text-center">Bentuk Zakat</th>
                            <th class="text-center">Nominal (Rp) / Jumlah (Kg)</th>
                            <!-- <th class="text-center">Jumlah (Kg)</th> -->
                            <!-- <th class="text-center">Keterangan</th>
                            <th class="text-center">Pengelola</th> -->

                            <th class="text-center">Aksi</th>

                        </tr>
                    </thead>
                    <tbody id="tabel-zakat">
                        @if ($zakatKeluar->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data zakat keluar</td>
                        </tr>
                        @else
                        @foreach ($zakatKeluar as $zk)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                <span class="badge bg-success bg-opacity-10 text-success">{{ $zakatKeluar->firstItem() + $loop->index }}
                            </td>
                            <td class="text-center">
                                @if(isset($zk->mustahik))
                                {{ $zk->mustahik->nama }}
                                @else
                                <i>Nama tidak ditemukan</i>
                                @endif
                            </td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($zk->tanggal)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $zk->jenis_zakat }}</td>
                            <td class="text-center">{{ $zk->bentuk_zakat }}</td>
                            <td class="text-center">
                                @if($zk->bentuk_zakat == 'Beras')
                                {{ $zk->jumlah == 0 ? '-' : rtrim(rtrim(number_format($zk->jumlah, 2, ',', '.'), '0'), ',') . ' Kg' }}
                                @else
                                {{ $zk->nominal == 0 ? '-' : 'Rp ' . number_format($zk->nominal, 0, ',', '.') }}
                                @endif
                            </td>
                            <!-- <td class="text-center">
                                @if($zk->jumlah == 0)
                                0 Kg
                                @else
                                {{ rtrim(rtrim(number_format($zk->jumlah, 2, ',', '.'), '0'), ',') }}
                                @endif
                            </td> -->
                            <!-- <td class="text-center">{{ $zk->keterangan }}</td>
                            <td class="text-center">
                                {{ $zk->user->nama ?? $zk->nama }}
                            </td> -->

                            <td class="text-center">
                                <!-- Tombol untuk membuka modal detail -->
                                <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                    data-bs-target="#modalDetailZakat{{ $zk->id }}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                @if(auth()->user()->role == 'Bendahara')
                                <a href="{{ route('zakat_keluar.edit', $zk->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pen-fill"></i>
                                </a>
                                <form id="delete-form-{{ $zk->id }}" action="{{ route('zakat_keluar.destroy', $zk->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('{{ $zk->id }}')">
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
                        Menampilkan {{ $zakatKeluar->firstItem() }} - {{ $zakatKeluar->lastItem() }} of {{ $zakatKeluar->total() }} rows
                    </div>
                </div>

                <!-- Pagination Navigation -->
                <div class="d-flex align-items-center">
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        {{-- Previous button --}}
                        @if ($zakatKeluar->onFirstPage())
                        <span class="btn-sm text-white opacity-50">
                            <i class="bi bi-chevron-left"></i> Previous
                        </span>
                        @else
                        <a href="{{ $zakatKeluar->previousPageUrl() }}" class="btn-sm text-white">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                        @endif

                        <span class="mx-2">Page {{ $zakatKeluar->currentPage() }} of {{ $zakatKeluar->lastPage() }}</span>

                        {{-- Next button --}}
                        @if ($zakatKeluar->currentPage() == $zakatKeluar->lastPage())
                        <span class="btn-sm text-white opacity-50">
                            Next <i class="bi bi-chevron-right"></i>
                        </span>
                        @else
                        <a href="{{ $zakatKeluar->nextPageUrl() }}" class="btn-sm text-white">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Zakat -->
@if (!$zakatKeluar->isEmpty())
@foreach ($zakatKeluar as $zk)
<div class="modal fade mt-4" id="modalDetailZakat{{ $zk->id }}" tabindex="-1" aria-labelledby="modalDetailZakatLabel{{ $zk->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-success text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="modalDetailZakatLabel{{ $zk->id }}">
                    <i class="bi bi-receipt-cutoff me-2"></i>Detail Zakat Keluar
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body bg-light p-4">
                <div class="row g-3">
                    @php
                    $formatTanggal = \Carbon\Carbon::parse($zk->tanggal)->translatedFormat('d F Y');
                    @endphp
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <small class="fw-semibold"><i class="bi bi-person-fill-check me-1"></i> Nama Mustahik</small>
                            <h6 class="text-dark mt-1">{{ $zk->mustahik->nama ?? '-' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <small class="fw-semibold"><i class="bi bi-person-fill-check me-1"></i> Kategori Mustahik</small>
                            <h6 class="text-dark mt-1">{{ $zk->mustahik->kategori ?? '-' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <small class="fw-semibold"><i class="bi bi-person-fill-check me-1"></i> Alamat Mustahik</small>
                            <h6 class="text-dark mt-1">{{ $zk->mustahik->alamat ?? '-' }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <small class="fw-semibold"><i class="bi bi-calendar3 me-1"></i> Tanggal</small>
                            <h6 class="text-dark mt-1">{{ $formatTanggal }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <small class="fw-semibold"><i class="bi bi-bookmark-check me-1"></i> Jenis Zakat</small>
                            <h6 class="text-dark mt-1">{{ $zk->jenis_zakat }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <small class="fw-semibold"><i class="bi bi-box2-heart me-1"></i> Bentuk Zakat</small>
                            <h6 class="text-dark mt-1">{{ $zk->bentuk_zakat }}</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <small class="fw-semibold">
                                <i class="bi bi-cash-stack me-1"></i>
                                @if ($zk->bentuk_zakat == 'Beras')
                                Jumlah (Kg)
                                @else
                                Nominal (Rp)
                                @endif
                            </small>
                            <h6 class="text-dark mt-1">
                                @if($zk->bentuk_zakat == 'Beras')
                                {{ $zk->jumlah == 0 ? '-' : rtrim(rtrim(number_format($zk->jumlah, 2, ',', '.'), '0'), ',') }} Kg
                                @else
                                {{ $zk->nominal == 0 ? '-' : 'Rp ' . number_format($zk->nominal, 0, ',', '.') }}
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <small class="fw-semibold"><i class="bi bi-person-check me-1"></i> Pengelola</small>
                            <h6 class="text-dark mt-1">{{ $zk->user->nama ?? $zk->nama ?? '-' }}</h6>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm p-3">
                            <small class="fw-semibold"><i class="bi bi-chat-text me-1"></i> Keterangan</small>
                            <p class="mt-2 mb-0 text-dark">{!! $zk->keterangan ?? '-' !!}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Terakhir diperbarui: {{ $zk->updated_at ? $zk->updated_at->format('d F Y') : 'Tidak diketahui' }}
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
            title: "Hapus Zakat Keluar",
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