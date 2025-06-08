@extends('layouts.app')

@section('content')
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
                <div class="input-group shadow rounded-3 overflow-hidden">
                    <input type="text" id="search" name="search" class="form-control form-control-sm border-0 bg-light px-3"
                        placeholder="Cari data infak..." value="{{ request('search') }}">
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
                            <th class="text-center">Tanggal Konfirmasi</th>
                            @if(in_array(auth()->user()->role, ['Bendahara', 'Petugas']))
                            <th class="text-center">Kuitansi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="infak">
                        @if($infakMasuk->isEmpty())
                        <tr>
                            <td colspan="12" class="text-center">Tidak ada data infak masuk</td>
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
                            <td class="text-center">{{ $im->buktiTransaksi->alamat }}</td>
                            <td class="text-center">{{ $im->buktiTransaksi->nomor_telepon }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($im->buktiTransaksi->tanggal_infak)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $im->buktiTransaksi->kategori }}</td>
                            <td class="text-center">{{ $im->buktiTransaksi->metode }}</td>
                            <td class="text-center">
                                @if($im->buktiTransaksi->nominal == 0)
                                <!-- 0 -->
                                @else
                                {{ number_format($im->buktiTransaksi->nominal, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="text-center">{{ $im->buktiTransaksi->barang }}</td>
                            <td class="text-center">
                                @if($im->buktiTransaksi->bukti_transaksi)
                                <a href="{{ asset('storage/' . $im->buktiTransaksi->bukti_transaksi) }}" target="_blank"
                                    style="color: black; text-decoration: none;" title="Lihat Bukti">
                                    <i class="bi bi-file-earmark-fill" style="font-size: 1.2rem;"></i>
                                </a>
                                @else
                                <i class="text-muted">Tidak ada</i>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($im->tanggal_konfirmasi)->format('d-m-Y') }}
                            </td>
                            @if(in_array(auth()->user()->role, ['Bendahara', 'Petugas']))
                            <td class="text-center text-xs text-secondary mb-0">
                                <a href="{{ route('infak.kuitansi.pdf', $im->id) }}"
                                    class="btn btn-sm btn-danger"
                                    title="Lihat Kuitansi PDF"
                                    target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </a>
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
                        Menampilkan {{ $infakMasuk->firstItem() }} - {{ $infakMasuk->lastItem() }} dari {{ $infakMasuk->total() }} data
                    </div>
                </div>

                <!-- Pagination Navigation -->
                <div class="d-flex align-items-center">
                    <!-- Previous and Next buttons -->
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        <a href="{{ $infakMasuk->previousPageUrl() }}" class="btn-sm text-white">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                        <span class="mx-2">Page {{ $infakMasuk->currentPage() }} of {{ $infakMasuk->lastPage() }}</span>
                        <a href="{{ $infakMasuk->nextPageUrl() }}" class="btn-sm text-white">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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