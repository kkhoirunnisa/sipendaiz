@extends('layouts.app')

@section('content')
<div class="px-3">
    <div class="mb-4" style="margin-left: 16px;">
        <div class="d-flex align-items-center mb-2">
            <div class="me-3">
                <div class="d-flex align-items-center justify-content-center bg-success bg-gradient text-white rounded-circle shadow-sm"
                    style="width: 60px; height: 60px;">
                    <i class="bi bi-archive-fill fs-4"></i>
                </div>
            </div>
            <div>
                <h3 class="mb-0 fw-bold text-dark">Laporan Infak {{ ucfirst($kategori) }} Masjid</h3>
                <div class="d-flex align-items-center d-none d-md-flex">
                    <span class="text-muted">Laporan</span>
                    <i class="bi bi-chevron-right mx-2 text-muted small"></i>
                    <span class="text-success fw-semibold">Infak {{ ucfirst($kategori) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-dark mb-1 fw-bold">Laporan Pemasukan dan Pengeluaran Infak {{ ucfirst($kategori) }}</h4>
                <p class="text-dark-50 mb-0 small">Data laporan infak masjid</p>
            </div>
            <div class="d-flex align-items-center mb-4">
                <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle me-3"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-funnel"></i>
                </div>
                <h5 class="mb-0 fw-semibold">Filter Laporan</h5>
            </div>

            <form action="{{ route('laporan_infak.generate') }}" method="GET" class="row g-3 mb-4">
                <input type="hidden" name="kategori" value="{{ $kategori }}">
                <!-- input tanggal awal -->
                <div class="col-lg-3 col-md-6">
                    <label for="tanggal_awal" class="form-label fw-semibold">
                        <i class="bi bi-calendar-event me-1 text-primary"></i>Tanggal Awal
                    </label>
                    <div class="input-group shadow">
                        <input type="date" name="tanggal_awal" id="tanggal_awal"
                            class="form-control border-start-0" required
                            value="{{ $startDate ?? '' }}" onchange="setMinTanggalAkhir()">
                    </div>
                </div>
                <!-- input tanggal akhir -->
                <div class="col-lg-3 col-md-6">
                    <label for="tanggal_akhir" class="form-label fw-semibold">
                        <i class="bi bi-calendar-check me-1 text-primary"></i>Tanggal Akhir
                    </label>
                    <div class="input-group shadow">
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                            class="form-control border-start-0" required
                            value="{{ $endDate ?? '' }}">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <label class="form-label fw-semibold text-white">.</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-search me-1"></i>Tampilkan Laporan
                        </button>

                        @if(isset($transactions) && count($transactions) > 0)
                        <button type="button" class="btn btn-outline-danger" onclick="resetFilter()">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                        </button>
                        <!-- download -->
                        <a href="{{ route('laporan_infak.download', ['tanggal_awal' => $startDate, 'tanggal_akhir' => $endDate, 'kategori' => $kategori]) }}"
                            class="btn btn-outline-danger">
                            <i class="bi bi-download me-1"></i>Unduh PDF
                        </a>

                        @endif
                    </div>
                </div>
            </form>
            <div class="alert alert-info border-0 bg-info bg-opacity-10 mt-3">
                <i class="bi bi-info-circle me-2"></i>
                <small>Data difilter berdasarkan <strong>tanggal konfirmasi</strong>, tetapi menampilkan tanggal infak.</small>
            </div>

            <!-- hasil laporan -->
            <div class="row">
                <div class="col-12">
                    @if(isset($transactions))
                    @if(count($transactions) > 0)
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                                            <i class="bi bi-arrow-down-circle text-success fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Total Pemasukan</h6>
                                            <h4 class="text-success mb-0 fw-bold">Rp {{ number_format($totalMasuk, 2, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 rounded p-3 me-3">
                                            <i class="bi bi-arrow-up-circle text-danger fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Total Pengeluaran</h6>
                                            <h4 class="text-danger mb-0 fw-bold">Rp {{ number_format($totalKeluar, 2, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                                            <i class="bi bi-cash-coin text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-1">Saldo Akhir</h6>
                                            <h4 class="text-primary mb-0 fw-bold">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0 py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-table text-primary"></i>
                                    </div>
                                    <h5 class="mb-0 fw-semibold">Detail Transaksi</h5>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                    {{ count($transactions) }} Transaksi
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive border shadow rounded-4">
                                <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 1000px">
                                    <thead class="table-gradient text-white">
                                        <tr>
                                            <th class="text-center border-0 py-3" style="width: 60px;">No</th>
                                            <th class="text-center border-0 py-3" style="width: 120px;">Tanggal Infak</th>
                                            <th class="text-center border-0 py-3">Keterangan</th>
                                            <th class="text-center border-0 py-3" style="width: 120px;">Barang</th>
                                            <th class="text-center border-0 py-3" style="width: 100px;">Transaksi</th>
                                            <th class="text-center border-0 py-3" style="width: 130px;">Masuk</th>
                                            <th class="text-center border-0 py-3" style="width: 130px;">Keluar</th>
                                            <th class="text-center border-0 py-3" style="width: 130px;">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $trx)
                                        <tr class="border-bottom">
                                            <td class="text-center">
                                                <span class="badge bg-success bg-opacity-10 text-success">{{ $trx['no'] }}</span>
                                            </td>
                                            <td class="text-center py-3">
                                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($trx['tanggal'])->format('d-m-Y') }}</span>
                                            </td>
                                            <td class="py-3">
                                                <div class="text-wrap text-center" style="max-width: 300px;">
                                                    @if ($trx['jenis_transaksi'] === 'Pemasukan')
                                                    @php
                                                    $donatur = $trx['donatur'] ?? '-';
                                                    $alamat = $trx['alamat'] ?? '-';
                                                    @endphp
                                                    Infak atas nama {{ $donatur }}@if (!empty($alamat) && $alamat !== '-') - {{ $alamat }}@endif
                                                    @else
                                                    Infak untuk {{ $trx['jenis_barang'] ?? '-' }} - {{ $trx['keterangan'] ?? '-' }}
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="text-center py-3">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                                    {{ $trx['jenis_barang'] }}
                                                </span>
                                            </td>
                                            <td class="text-center py-3">
                                                @if($trx['jenis_transaksi'] == 'Pemasukan')
                                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">
                                                    <i class="bi bi-arrow-down me-1"></i>{{ $trx['jenis_transaksi'] }}
                                                </span>
                                                @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">
                                                    <i class="bi bi-arrow-up me-1"></i>{{ $trx['jenis_transaksi'] }}
                                                </span>
                                                @endif
                                            </td>
                                            <td class="text-end py-3">
                                                @if ($trx['masuk'] == 0)
                                                <span class="text-muted">-</span>
                                                @else
                                                <span class="text-success fw-semibold">Rp {{ number_format($trx['masuk'], 2, ',', '.') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end py-3">
                                                @if ($trx['keluar'] == 0)
                                                <span class="text-muted">-</span>
                                                @else
                                                <span class="text-danger fw-semibold">Rp {{ number_format($trx['keluar'], 2, ',', '.') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-end py-3">
                                                <span class="fw-bold">Rp {{ number_format($trx['saldo'], 2, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>


                                    <!-- Table Footer Summary -->
                                    <div class="border-top bg-light">
                                        <div class="table-responsive">
                                            <tr class="border-0">
                                                <td colspan="5" class="text-end fw-bold border-0">
                                                    <h6 class="mb-0 py-3 fw-bold">
                                                        <span class="badge bg-info bg-opacity-50 text-dark px-2 py-1">RINGKASAN TOTAL
                                                        </span>
                                                    </h6>
                                                </td>
                                                <td class="text-end border-0">
                                                    <div class="text-success fw-bold">
                                                        Rp {{ number_format($totalMasuk, 2, ',', '.') }}
                                                    </div>
                                                    <small class="text-muted">Total Masuk</small>
                                                </td>
                                                <td class="text-end border-0">
                                                    <div class="text-danger fw-bold">
                                                        Rp {{ number_format($totalKeluar, 2, ',', '.') }}
                                                    </div>
                                                    <small class="text-muted">Total Keluar</small>
                                                </td>
                                                <td class="text-end border-0">
                                                    <div class="fw-bold text-primary">
                                                        Rp {{ number_format($totalSaldo, 2, ',', '.') }}
                                                    </div>
                                                    <small class="text-muted">Saldo Akhir</small>
                                                </td>
                                            </tr>
                                        </div>
                                    </div>
                                </table>
                            </div>
                        </div>
                        <!-- pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap px-2">
                            <!-- Pagination Info -->
                            <div class="d-flex align-items-center">
                                <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} data
                                </div>
                            </div>

                            <!-- Pagination Navigation -->
                            <div class="d-flex align-items-center">
                                <!-- Previous and Next buttons -->
                                <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                                    {{-- Previous button --}}
                                    @if ($transactions->onFirstPage())
                                    <span class="btn-sm text-white opacity-50">
                                        <i class="bi bi-chevron-left"></i> Previous
                                    </span>
                                    @else
                                    <a href="{{ $transactions->previousPageUrl() }}" class="btn-sm text-white">
                                        <i class="bi bi-chevron-left"></i> Previous
                                    </a>
                                    @endif

                                    <span class="mx-2">Page {{ $transactions->currentPage() }} of {{ $transactions->lastPage() }}</span>

                                    {{-- Next button --}}
                                    @if ($transactions->currentPage() == $transactions->lastPage())
                                    <span class="btn-sm text-white opacity-50">
                                        Next <i class="bi bi-chevron-right"></i>
                                    </span>
                                    @else
                                    <a href="{{ $transactions->nextPageUrl() }}" class="btn-sm text-white">
                                        Next <i class="bi bi-chevron-right"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- No Data State -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                                </div>
                                <h5 class="text-muted mb-3">Tidak Ada Data</h5>
                                <p class="text-muted mb-4">Data laporan tidak tersedia untuk rentang tanggal yang dipilih.</p>
                                <button type="button" class="btn btn-primary" onclick="resetFilter()">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                                </button>
                            </div>
                        </div>
                        @endif
                        @else
                        <!-- Initial State -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-calendar-range display-1 text-primary opacity-50"></i>
                                </div>
                                <h5 class="text-primary mb-3">Pilih Rentang Tanggal</h5>
                                <p class="text-muted mb-0">Silakan pilih rentang tanggal dan klik tombol "Tampilkan Laporan" untuk melihat data transaksi.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        const tanggalAwal = document.getElementById('tanggal_awal').value;
        const tanggalAkhir = document.getElementById('tanggal_akhir').value;

        if (tanggalAwal > tanggalAkhir) {
            e.preventDefault();
            alert('Tanggal Awal tidak boleh lebih besar dari Tanggal Akhir.');
        }
    });

    function setMinTanggalAkhir() {
        const awal = document.getElementById('tanggal_awal').value;
        document.getElementById('tanggal_akhir').min = awal;
    }

    // Panggil fungsi saat halaman dimuat untuk menjaga nilai jika user kembali ke halaman
    window.onload = setMinTanggalAkhir;

    function resetFilter() {
        window.location.href = "{{ route('laporan_infak.index', ['kategori' => $kategori]) }}";
    }
</script>
@endsection