@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="min-height: 100vh;">
    <!-- Header Welcome -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                <div class="card-body text-center py-4">
                    <h2 class="text-success mb-2 fw-bold">
                        <i class="bi bi-house-heart me-2"></i>
                        Selamat Datang, {{ auth()->user()->nama }}
                    </h2>
                    <small class="text-muted">Sistem Informasi Pengelolaan Dana Infak dan Zakat Masjid</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Infak Pembangunan -->
    <div class="row">
        <div class="col-12 mb-3">
            <h4 class="text-dark fw-bold mb-0">
                <i class="bi bi-building me-2"></i>
                Dana Infak Pembangunan
            </h4>
            <hr class="text-dark">
        </div>

        <!-- Total Dana Infak Pembangunan -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL DANA</p>
                            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalInfakPembangunan, 0, ',', '.') }}</h4>
                            <span class="badge bg-info bg-opacity-10 text-info small">
                                <i class="bi bi-graph-up me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-wallet text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemasukan Infak Pembangunan -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PEMASUKAN HARI INI</p>
                            <h4 class="fw-bold mb-1 text-success">Rp {{ number_format($pemasukanInfakPembangunanHariIni, 0, ',', '.') }}</h4>
                            <span class="badge bg-success bg-opacity-10 text-success small">
                                <i class="bi bi-calendar-check me-1"></i>{{ date('d F Y') }}
                            </span>
                        </div>
                        <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-down-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Infak Pembangunan -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PENGELUARAN HARI INI</p>
                            <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($pengeluaranInfakPembangunanHariIni, 0, ',', '.') }}</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-calendar-check me-1"></i>{{ date('d F Y') }}
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-up-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Infak Pembangunan -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL PENGELUARAN</p>
                            <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($totalPengeluaranInfakPembangunan, 0, ',', '.') }}</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-graph-down me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-cash-coin text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics 7 Hari Terakhir -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PEMASUKAN 7 HARI</p>
                            <h4 class="fw-bold mb-1 text-warning">Rp {{ number_format($pemasukanInfakPembangunan7Hari, 0, ',', '.') }}</h4>
                            <span class="badge bg-warning bg-opacity-10 text-warning small">
                                <i class="bi bi-calendar-week me-1"></i>7 Hari Dari Hari Ini
                            </span>
                        </div>
                        <div class="bg-warning bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-calendar-week text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- pengeluaran 7 hari -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PENGELUARAN 7 HARI</p>
                            <h4 class="fw-bold mb-1 text-warning">Rp {{ number_format($pengeluaranInfakPembangunan7Hari, 0, ',', '.') }}</h4>
                            <span class="badge bg-warning bg-opacity-10 text-warning small">
                                <i class="bi bi-calendar-week me-1"></i>7 Hari Dari Hari Ini
                            </span>
                        </div>
                        <div class="bg-warning bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-calendar-week text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- sisa infak -->
    <div class="col mb-4">
        <div class="card border-0 shadow-lg h-100 card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">SISA INFAK PEMBANGUNAN</p>
                        <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($saldoInfakPembangunan, 0, ',', '.') }}</h4>
                        <span class="badge bg-danger bg-opacity-10 text-danger small">
                            <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                        </span>
                    </div>
                    <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-arrow-up-circle text-white" style="font-size: 1.8rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Infak Takmir -->
    <div class="row">
        <div class="col-12 mb-3">
            <h4 class="text-dark fw-bold mb-0">
                <i class="bi bi-people me-2"></i>
                Dana Infak Takmir
            </h4>
            <hr class="text-dark">
        </div>

        <!-- Total Dana Infak Takmir -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL DANA</p>
                            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalInfakTakmir, 0, ',', '.') }}</h4>
                            <span class="badge bg-info bg-opacity-10 text-info small">
                                <i class="bi bi-graph-up me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-wallet text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemasukan Infak Takmir -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PEMASUKAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-success">Rp {{ number_format($pemasukanInfakTakmirBulanIni, 0, ',', '.') }}</h4>
                            <span class="badge bg-success bg-opacity-10 text-success small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-down-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Infak Takmir -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PENGELUARAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($pengeluaranInfakTakmirBulanIni, 0, ',', '.') }}</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-up-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Infak Takmir -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL PENGELUARAN</p>
                            <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($totalPengeluaranInfakTakmir, 0, ',', '.') }}</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-graph-down me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-cash-coin text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- sisa infak -->
    <div class="col mb-4">
        <div class="card border-0 shadow-lg h-100 card-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">SISA INFAK TAKMIR</p>
                        <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($saldoInfakTakmir, 0, ',', '.') }}</h4>
                        <span class="badge bg-danger bg-opacity-10 text-danger small">
                            <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                        </span>
                    </div>
                    <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-arrow-up-circle text-white" style="font-size: 1.8rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Transaksi Pending -->
    @if(in_array(auth()->user()->role, ['Bendahara', 'Petugas']))
    <div class="row mb-5">
        <div class="col-12 mb-3">
            <h4 class="text-dark fw-bold mb-0">
                <i class="bi bi-clock-history me-2"></i>
                Transaksi Menunggu Konfirmasi
            </h4>
            <hr class="text-dark">
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient" style="background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);">
                    <h5 class="card-title mb-0 text-success fw-bold">
                        <i class="bi bi-list-check me-2"></i>
                        Daftar Transaksi Pending
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center fw-bold">No</th>
                                    <th class="fw-bold">Nama</th>
                                    <th class="fw-bold">Alamat</th>
                                    <th class="text-center fw-bold">Tanggal</th>
                                    <th class="text-center fw-bold">Metode</th>
                                    <th class="text-end fw-bold">Nominal</th>
                                    <th class="text-center fw-bold">Bukti</th>
                                    <th class="text-center fw-bold">Status</th>
                                    <th class="text-center fw-bold">Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksiPending as $index => $transaksi)
                                <tr class="align-middle">
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="fw-semibold">{{ $transaksi->donatur }}</td>
                                    <td class="text-muted small">{{ $transaksi->alamat }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            {{ \Carbon\Carbon::parse($transaksi->tanggal_infak)->format('d-m-Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $transaksi->metode }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($transaksi->bukti_transaksi)
                                        <a href="{{ asset('bukti/' . $transaksi->bukti_transaksi) }}" target="_blank"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-file-earmark-image"></i>
                                        </a>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock me-1"></i>{{ $transaksi->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-muted small">{{ $transaksi->user->nama ?? '-' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                            <p class="mb-0">Tidak ada transaksi yang menunggu konfirmasi</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Count Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #ffd700 0%, #ffb300 100%);">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">Menunggu Konfirmasi</h5>
                    <div class="display-2 fw-bold text-dark mb-2">{{ $totalPendingTransaksi }}</div>
                    <span class="badge bg-danger bg-opacity-20 text-danger">
                        <i class="bi bi-clock me-1"></i>Data Transaksi
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Section Zakat Uang Fitrah -->
    <div class="row">
        <div class="col-12 mb-3">
            <h4 class="text-dark fw-bold mb-0">
                <i class="bi bi-cash-coin me-2"></i>
                Zakat Uang Fitrah
            </h4>
            <hr class="text-dark">
        </div>

        <!-- Total Zakat Uang Fitrah -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL ZAKAT UANG FITRAH</p>
                            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalZakatUangFitrah, 0, ',', '.') }}</h4>
                            <span class="badge bg-info bg-opacity-10 text-info small">
                                <i class="bi bi-graph-up me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-wallet text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemasukan Zakat Uang Fitrah -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PEMASUKAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-success">Rp {{ number_format($pemasukanZakatUangFitrahBulanIni, 0, ',', '.') }}</h4>
                            <span class="badge bg-success bg-opacity-10 text-success small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-down-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Zakat Uang Fitrah -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PENGELUARAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($pengeluaranZakatUangFitrahBulanIni, 0, ',', '.') }}</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-up-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Zakat Uang Fitrah -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL PENGELUARAN</p>
                            <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($totalPengeluaranZakatUangFitrah, 0, ',', '.') }}</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-graph-down me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-cash-coin text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sisa Zakat Uang Fitrah -->
        <div class="col mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">SISA ZAKAT UANG FITRAH</p>
                            <h4 class="fw-bold mb-1 text-primary">Rp {{ number_format($sisaZakatUangFitrah, 0, ',', '.') }}</h4>
                            <span class="badge bg-primary bg-opacity-10 text-primary small">
                                <i class="bi bi-wallet me-1"></i>Tersisa
                            </span>
                        </div>
                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-wallet text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Zakat Uang Maal -->
    <div class="row">
        <div class="col-12 mb-3">
            <h4 class="text-dark fw-bold mb-0">
                <i class="bi bi-cash-coin me-2"></i>
                Zakat Uang Maal
            </h4>
            <hr class="text-dark">
        </div>

        <!-- Total Zakat Uang Maal -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL ZAKAT UANG MAAL</p>
                            <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalZakatUangMaal, 0, ',', '.') }}</h4>
                            <span class="badge bg-info bg-opacity-10 text-info small">
                                <i class="bi bi-graph-up me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-cash-coin text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemasukan Zakat Uang Maal -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PEMASUKAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-success">Rp {{ number_format($pemasukanZakatUangMaalBulanIni, 0, ',', '.') }}</h4>
                            <span class="badge bg-success bg-opacity-10 text-success small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-down-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Zakat Uang Maal -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PENGELUARAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($pengeluaranZakatUangMaalBulanIni, 0, ',', '.') }}</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-up-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Zakat Uang Maal -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL PENGELUARAN</p>
                            <h4 class="fw-bold mb-1 text-danger">Rp {{ number_format($totalPengeluaranZakatUangMaal, 0, ',', '.') }}</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-graph-down me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-cash-coin text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sisa Zakat Uang Maal -->
        <div class="col mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">SISA ZAKAT UANG MAAL</p>
                            <h4 class="fw-bold mb-1 text-primary">Rp {{ number_format($sisaZakatUangMaal, 0, ',', '.') }}</h4>
                            <span class="badge bg-primary bg-opacity-10 text-primary small">
                                <i class="bi bi-cash-coin me-1"></i>Tersisa
                            </span>
                        </div>
                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-cash-coin text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Zakat Beras Fitrah -->
    <div class="row mb-5">
        <div class="col-12 mb-3">
            <h4 class="text-dark fw-bold mb-0">
                <i class="bi bi-box2 me-2"></i>
                Zakat Beras Fitrah
            </h4>
            <hr class="text-dark">
        </div>

        <!-- Total Zakat Beras Fitrah -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL ZAKAT BERAS FITRAH</p>
                            <h4 class="fw-bold mb-1 text-dark">{{ rtrim(rtrim(number_format($totalZakatBerasFitrah, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-info bg-opacity-10 text-info small">
                                <i class="bi bi-graph-up me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-box text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemasukan Zakat Beras Fitrah -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PEMASUKAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-success">{{ rtrim(rtrim(number_format($pemasukanZakatBerasFitrahBulanIni, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-success bg-opacity-10 text-success small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-down-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Zakat Beras Fitrah -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PENGELUARAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-danger">{{ rtrim(rtrim(number_format($pengeluaranZakatBerasFitrahBulanIni, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-up-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Zakat Beras Fitrah -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL PENGELUARAN</p>
                            <h4 class="fw-bold mb-1 text-danger">{{ rtrim(rtrim(number_format($totalPengeluaranZakatBerasFitrah, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-graph-down me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-box text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sisa Zakat Beras Fitrah -->
        <div class="col mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">SISA ZAKAT BERAS FITRAH</p>
                            <h4 class="fw-bold mb-1 text-primary">{{ rtrim(rtrim(number_format($sisaZakatBerasFitrah, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-primary bg-opacity-10 text-primary small">
                                <i class="bi bi-box me-1"></i>Tersisa
                            </span>
                        </div>
                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-box text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Zakat Beras Maal -->
    <div class="row mb-5">
        <div class="col-12 mb-3">
            <h4 class="text-dark fw-bold mb-0">
                <i class="bi bi-box2 me-2"></i>
                Zakat Beras Maal
            </h4>
            <hr class="text-dark">
        </div>

        <!-- Total Zakat Beras Maal -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL ZAKAT BERAS MAAL</p>
                            <h4 class="fw-bold mb-1 text-dark">{{ rtrim(rtrim(number_format($totalZakatBerasMaal, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-info bg-opacity-10 text-info small">
                                <i class="bi bi-graph-up me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-box2 text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemasukan Zakat Beras Maal -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PEMASUKAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-success">{{ rtrim(rtrim(number_format($pemasukanZakatBerasMaalBulanIni, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-success bg-opacity-10 text-success small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-down-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Zakat Beras Maal -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">PENGELUARAN BULAN INI</p>
                            <h4 class="fw-bold mb-1 text-danger">{{ rtrim(rtrim(number_format($pengeluaranZakatBerasMaalBulanIni, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-calendar-month me-1"></i>{{ date('F Y') }}
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-arrow-up-circle text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Zakat Beras Maal -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">TOTAL PENGELUARAN</p>
                            <h4 class="fw-bold mb-1 text-danger">{{ rtrim(rtrim(number_format($totalPengeluaranZakatBerasMaal, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger small">
                                <i class="bi bi-graph-down me-1"></i>Keseluruhan
                            </span>
                        </div>
                        <div class="bg-danger bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-box2 text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sisa Zakat Beras Maal -->
        <div class="col mb-4">
            <div class="card border-0 shadow-lg h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small fw-semibold">SISA ZAKAT BERAS MAAL</p>
                            <h4 class="fw-bold mb-1 text-primary">{{ rtrim(rtrim(number_format($sisaZakatBerasMaal, 2, ',', '.'), '0'), ',') }} kg</h4>
                            <span class="badge bg-primary bg-opacity-10 text-primary small">
                                <i class="bi bi-box2 me-1"></i>Tersisa
                            </span>
                        </div>
                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-box2 text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection