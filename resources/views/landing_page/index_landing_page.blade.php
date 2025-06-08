<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masjid Jami' Al Munawwarah - SIPENDAIZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-green: #2d7d32;
            --dark-green: #1b5e20;
            --light-green: #e8f5e8;
            --accent-gold: #ffd700;
            --text-dark: #2c3e50;
            --gradient-primary: linear-gradient(135deg, #2d7d32, #1b5e20);
            --gradient-gold: linear-gradient(135deg, #ffd700, #ffb300);
            --shadow-soft: 0 10px 40px rgba(45, 125, 50, 0.1);
            --shadow-medium: 0 20px 60px rgba(45, 125, 50, 0.15);
            --shadow-strong: 0 30px 80px rgba(45, 125, 50, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Header Styles */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            padding: 1rem 0;
        }

        .navbar-custom.scrolled {
            padding: 0.5rem 0;
            box-shadow: var(--shadow-medium);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        .logo-container {
            width: 50px;
            height: 50px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-soft);
            transition: transform 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .logo-text h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-green);
            margin: 0;
        }

        .logo-text small {
            color: #6c757d;
            font-weight: 500;
        }

        .btn-login {
            background: var(--gradient-primary);
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-soft);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            color: white;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg,
                    rgba(45, 125, 50, 0.9),
                    rgba(27, 94, 32, 0.8)),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><defs><pattern id="islamic" patternUnits="userSpaceOnUse" width="100" height="100"><circle cx="50" cy="50" r="30" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/><path d="M20,50 Q50,20 80,50 Q50,80 20,50" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="1200" height="800" fill="url(%23islamic)"/></svg>') center/cover;
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            color: white;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255, 215, 0, 0.1) 0%, transparent 50%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            font-weight: 500;
            opacity: 0.95;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
        }

        .mosque-icon-hero {
            font-size: 8rem;
            color: var(--accent-gold);
            margin-bottom: 2rem;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Statistics Cards */
        .stats-section {
            background: linear-gradient(to bottom, var(--light-green), white);
            padding: 100px 0;
            position: relative;
        }

        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-medium);
            transition: all 0.3s ease;
            border: 1px solid rgba(45, 125, 50, 0.1);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-primary);
        }

        .stats-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-strong);
        }

        .card-header-custom {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .card-icon {
            width: 70px;
            height: 70px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-soft);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .amount-display {
            font-size: 2.2rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 1rem 0;
        }

        .transaction-count {
            background: var(--light-green);
            padding: 15px;
            border-radius: 10px;
            margin: 1.5rem 0;
        }

        .transaction-count h4 {
            font-weight: 700;
            color: var(--primary-green);
            margin: 0;
        }

        .transaction-count p {
            color: #666;
            margin: 0;
        }

        /* Table Styles */
        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
        }

        .table-custom thead {
            background: var(--gradient-primary);
        }

        .table-custom thead th {
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .table-custom tbody td {
            padding: 15px;
            border-color: rgba(45, 125, 50, 0.1);
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background: rgba(45, 125, 50, 0.05);
        }

        .amount-success {
            color: var(--primary-green);
            font-weight: 700;
        }

        .amount-primary {
            color: #0d6efd;
            font-weight: 700;
        }

        .expense-card {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            border-radius: 15px;
            padding: 20px;
            margin-top: 1.5rem;
        }

        .expense-card-sisa {
            background: linear-gradient(135deg, rgb(226, 254, 239), rgb(213, 254, 202));
            border: 1px solidrgb(165, 252, 175);
            border-radius: 15px;
            padding: 20px;
            margin-top: 1.5rem;
        }

        .expense-amount {
            font-size: 1.5rem;
            font-weight: 800;
            color: #dc2626;
        }

        .expense-amount-sisa {
            font-size: 1.5rem;
            font-weight: 800;
            color: rgb(25, 143, 70);
        }

        /* Footer */
        .footer-custom {
            background: var(--gradient-primary);
            color: white;
            padding: 60px 0 30px;
            position: relative;
        }

        .footer-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 400"><defs><pattern id="footer-pattern" patternUnits="userSpaceOnUse" width="60" height="60"><circle cx="30" cy="30" r="20" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="1200" height="400" fill="url(%23footer-pattern)"/></svg>');
        }

        .footer-content {
            position: relative;
            z-index: 2;
        }

        .footer-section h6 {
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--accent-gold);
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--accent-gold);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 40px;
            padding-top: 30px;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .mosque-icon-hero {
                font-size: 5rem;
            }

            .stats-card {
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .mosque-icon-hero {
                font-size: 4rem;
            }

            .stats-card {
                padding: 1.5rem;
            }

            .amount-display {
                font-size: 1.8rem;
            }
        }

        /* Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in-up.animate {
            opacity: 1;
            transform: translateY(0);
        }

        .scale-in {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.6s ease;
        }

        .scale-in.animate {
            opacity: 1;
            transform: scale(1);
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-green);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--dark-green);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <div class="logo-container">
                    <i class="fas fa-mosque text-white fs-4"></i>
                </div>
                <div class="logo-text">
                    <h1>Masjid Jami' Al Munawwarah</h1>
                </div>
            </a>
            <a href="/login" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center hero-content">
                    <div class="mosque-icon-hero" data-aos="zoom-in">
                        <i class="fas fa-mosque"></i>
                    </div>
                    <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
                        SELAMAT DATANG DI<br>
                        <span style="color: var(--accent-gold);">WEBSITE</span>
                    </h1>
                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="400">
                        Masjid Jami' Al Munawwarah
                    </p>
                    <div class="mt-4" data-aos="fade-up" data-aos-delay="600">
                        <div class="d-inline-flex align-items-center bg-white bg-opacity-20 rounded-pill px-4 py-2">
                            <i class="fas fa-hand-holding-heart me-3 text-warning"></i>
                            <span class="fw-semibold text-dark">Bersama membangun masjid untuk kebaikkan umat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <!-- Infak Pembangunan Card -->
                <div class="col-lg-6 mb-4" data-aos="fade-right">
                    <div class="stats-card">
                        <div class="card-header-custom">
                            <div>
                                <h2 class="card-title">Dana Infak Pembangunan Masjid</h2>
                                <p class="text-muted mb-0">Pembangunan & Renovasi Masjid</p>
                                <span class="badge bg-success">Total Infak</span>
                            </div>

                        </div>

                        <div class="amount-display">
                            Rp {{ number_format($totalInfakPembangunan, 0, ',', '.') }}
                        </div>

                        <div class="transaction-count">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1">Total Transaksi</p>
                                    <h4>{{ number_format($jumlahTransaksiPembangunan) }}</h4>
                                </div>
                                <div class="text-end">
                                    <i class="fas fa-chart-line fs-2 text-success"></i>
                                </div>
                            </div>
                        </div>

                        <h6 class="mb-3 fw-bold">
                            <i class="fas fa-users me-2"></i>Donatur Terkini
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-dark">
                                            <i class="fas fa-user me-2 text-dark"></i>Nama
                                        </th>
                                        <th class="text-dark">
                                            <i class="fas fa-money-bill me-2 text-dark"></i>Infak
                                        </th>
                                        <th class="text-dark">
                                            <i class="fas fa-calendar me-2 text-dark"></i>Tanggal
                                        </th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @forelse($donaturTerkini as $donatur)
                                    <tr>
                                        <td>{{ $donatur->buktiTransaksi->donatur }}</td>
                                        <td class="text-success">
                                            @if (!empty($donatur->buktiTransaksi->barang))
                                            {{ $donatur->buktiTransaksi->barang }}
                                            @elseif (!empty($donatur->buktiTransaksi->nominal))
                                            Rp {{ number_format($donatur->buktiTransaksi->nominal, 0, ',', '.') }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($donatur->buktiTransaksi->tanggal_infak)->format('d-m-Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada donatur terkini</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        <div class="expense-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-arrow-down me-2"></i>Total Pengeluaran Pembangunan
                                    </p>
                                    <div class="expense-amount">Rp {{ number_format($totalPengeluaranInfakPembangunan, 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <i class="fas fa-receipt fs-2 text-danger"></i>
                                </div>
                            </div>
                        </div>
                        <div class="expense-card-sisa">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-chart-line me-2"></i>Sisa Saldo Infak Pembangunan
                                    </p>
                                    <div class="expense-amount-sisa">Rp {{ number_format($saldoInfakPembangunan, 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <i class="fas fa-receipt fs-2 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Infak Takmir Card -->
                <div class="col-lg-6 mb-4" data-aos="fade-left">
                    <div class="stats-card">
                        <div class="card-header-custom">
                            <div>
                                <h2 class="card-title">Dana Infak Takmir Masjid</h2>
                                <p class="text-muted mb-0">Operasional & Kegiatan Masjid</p>
                                <span class="badge bg-success">Total Infak</span>
                            </div>

                        </div>

                        <div class="amount-display">
                            Rp {{ number_format($totalInfakTakmir, 0, ',', '.') }}
                        </div>

                        <h6 class="mb-3 fw-bold">
                            <i class="fas fa-list me-2"></i>Pendapatan Terkini
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-dark">
                                            <i class="fas fa-user me-2 text-dark"></i>Sumber
                                        </th>
                                        <th class="text-dark">
                                            <i class="fas fa-money-bill me-2 text-dark"></i>Nominal
                                        </th>
                                        <th class="text-dark">
                                            <i class="fas fa-calendar me-2 text-dark"></i>Tanggal
                                        </th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php $recentInfak = $pendapatantakmirterkini->take(3); @endphp
                                    @forelse($recentInfak as $infak)
                                    <tr>
                                        <td>{{ $infak->buktiTransaksi->donatur }}</td>
                                        <td class="text-success">Rp {{ number_format($infak->buktiTransaksi->nominal, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($infak->buktiTransaksi->tanggal_infak)->format('d-m-Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada data infak terkini</td>
                                    </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        <div class="expense-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-arrow-down me-2"></i>Total Pengeluaran Takmir
                                    </p>
                                    <div class="expense-amount"> Rp {{ number_format($totalPengeluaranInfakTakmir, 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <i class="fas fa-calculator fs-2 text-danger"></i>
                                </div>
                            </div>
                        </div>
                        <div class="expense-card-sisa">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-chart-line me-2"></i>Sisa Saldo Infak Takmir
                                    </p>
                                    <div class="expense-amount-sisa">Rp {{ number_format($saldoInfakTakmir, 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <i class="fas fa-receipt fs-2 text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-12">
                    <div class="stats-card">
                        <div class="row text-center justify-content-center">
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-mosque text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">1</h3>
                                <p class="text-muted">Masjid Dikelola</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-users text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">{{ number_format($jumlahDonatur) }}</h3>
                                <p class="text-muted">Donatur Aktif</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-chart-line text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">{{ number_format($jumlahTransaksiInfak) }}</h3>
                                <p class="text-muted">Total Transaksi</p>
                            </div>
                            <!-- <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">Rp {{ number_format($totalInfak, 0, ',', '.') }}</h3>
                                <p class="text-muted">Total Dana Terkumpul</p>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik Zakat Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <!-- Zakat Summary Card -->
                <div class="col-lg-12 mb-4" data-aos="fade-up">
                    <div class="stats-card">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h2 class="card-title">Informasi Zakat Masjid</h2>
                            <span class="badge bg-success">Terbaru</span>
                        </div>

                        <div class="d-flex justify-content-between flex-wrap text-center mb-4">
                            <div class="flex-fill mx-2">
                                <p class="fw-bold">Total Transaksi Pemasukan</p>
                                <h5 class="amount-display">{{ $totalTransaksiZakatMasuk }}</h5>
                            </div>
                            <div class="flex-fill mx-2">
                                <p class="fw-bold">Total Mustahik</p>
                                <h5 class="amount-display">{{ number_format($totalMustahik) }}</h5>
                            </div>
                        </div>
                        <!-- <div class="col-md-4">
                                <p class="text-muted mb-1">Total Beras (Kg)</p>
                                <h3 class="fw-bold text-success">{{ number_format($totalZakatBeras, 0, ',', '.') }} Kg</h3>
                            </div> -->


                        <!-- Zakat Terkini Table -->
                        <h6 class="mb-3 fw-bold">
                            <i class="fas fa-donate me-2"></i>Zakat Terkini
                        </h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-custom table-success table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-dark">
                                            <i class="fas fa-user me-2 text-dark"></i>Nama
                                        </th>
                                        <th class="text-dark">
                                            <i class="fas fa-weight me-2 text-dark"></i>Beras
                                        </th>
                                        <th class="text-dark">
                                            <i class="fas fa-money-bill me-2 text-dark"></i>Uang
                                        </th>
                                        <th class="text-dark">
                                            <i class="fas fa-tags me-2 text-dark"></i>Jenis Zakat
                                        </th>
                                        <th class="text-dark">
                                            <i class="fas fa-calendar me-2 text-dark"></i>Tanggal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($zakatTerkini as $zakat)
                                    <tr>
                                        <td class="text-dark">{!! nl2br(e($zakat->keterangan)) !!}</td>
                                        <td class="text-success">
                                            @if(strtolower($zakat->bentuk_zakat) === 'beras')
                                            {{ ($zakat->jumlah ?? 0) == 0 ? '0' : rtrim(rtrim(number_format($zakat->jumlah, 2, ',', '.'), '0'), ',') }} Kg
                                            @else
                                            -
                                            @endif
                                        </td>

                                        <td class="text-success">
                                            @if(strtolower($zakat->bentuk_zakat) === 'uang')
                                            Rp {{ number_format($zakat->nominal ?? 0, 0, ',', '.') }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="text-dark">{{ $zakat->jenis_zakat }}</td>
                                        <td class="text-dark">{{ \Carbon\Carbon::parse($zakat->tanggal)->format('d-m-Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Muzaki dan Mustahik Table -->
                        <div class="row mt-4">
                            <!-- Daftar Muzaki -->

                            <!-- Daftar Mustahik -->
                            <div class="table-responsive mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold mb-0">Mustahik</h6>
                                    <a href="{{ route('mustahik.pdf') }}" class="badge bg-success text-decoration-none">Lihat Mustahik</a>
                                </div>
                                <ul class="list-group list-group-numbered shadow-sm rounded" id="mustahikList">
                                    @foreach($listMustahik as $nama)
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                        <span class="mx-auto text-dark">{{ $nama }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Summary Statistics -->
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-12 ">
                    <div class="stats-card">
                        <h2 class="card-title">Informasi Zakat Fitrah</h2>
                        <div class="row text-center justify-content-center mt-4">
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">Rp {{ number_format($totalZakatUangFitrah, 0, ',', '.') }}</h3>
                                <p class="text-muted">Total Uang</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">{{ rtrim(rtrim(number_format($totalZakatBerasFitrah, 2, ',', '.'), '0'), ',') }} kg</h3>
                                <p class="text-muted">Total Beras</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">Rp {{ number_format($sisaZakatUangFitrah, 0, ',', '.') }}</h3>
                                <p class="text-muted">Sisa Uang</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">{{ rtrim(rtrim(number_format($sisaZakatBerasFitrah, 2, ',', '.'), '0'), ',') }} kg</h3>
                                <p class="text-muted">Sisa Beras</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-12 ">
                    <div class="stats-card">
                        <h2 class="card-title">Informasi Zakat Maal</h2>
                        <div class="row text-center justify-content-center mt-4">
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">Rp {{ number_format($totalZakatUangMaal, 0, ',', '.') }}</h3>
                                <p class="text-muted">Total Uang</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">{{ rtrim(rtrim(number_format($totalZakatBerasMaal, 2, ',', '.'), '0'), ',') }} kg</h3>
                                <p class="text-muted">Total Beras</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">Rp {{ number_format($sisaZakatUangMaal, 0, ',', '.') }}</h3>
                                <p class="text-muted">Sisa Uang</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">{{ rtrim(rtrim(number_format($sisaZakatBerasMaal, 2, ',', '.'), '0'), ',') }} kg</h3>
                                <p class="text-muted">Sisa Beras</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik Info Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <!-- Infak Pembangunan Summary Card -->
                <div class="col-lg-12 mb-4" data-aos="fade-up">
                    <div class="stats-card">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h2 class="card-title">Konfirmasi Transfer dan Informasi Infak Pembangunan Masjid</h2>
                            <span class="badge bg-success">Terbaru</span>
                        </div>

                        <!-- Informasi Table -->
                        <h6 class="mb-3 fw-bold">
                            <i class="fas fa-donate me-2"></i>Konfirmasi Transfer Infak Pembangunan Masjid
                        </h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-custom table-success table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-dark text-center">
                                            <i class="fas fa-user me-2 text-dark"></i>Nama
                                        </th>
                                        <th class="text-dark text-center">
                                            <i class="fas fa-weight me-2 text-dark"></i>Nomor WhatsApp
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-dark text-center">Beni</td>
                                        <td class="text-dark text-center">082193847566</td>
                                    </tr>
                                    <tr>
                                        <td class="text-dark  text-center">Agus</td>
                                        <td class="text-dark text-center">093846284628</td>
                                    </tr>
                                    <tr>
                                        <td class="text-dark text-center">Syarif</td>
                                        <td class="text-dark text-center">029482648599</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Rekening Statistics -->
            <div class="row mt-5" data-aos="fade-up">
                <div class="col-12 ">
                    <div class="stats-card">
                        <div class="card-header-custom d-flex justify-content-between align-items-center">
                            <h2 class="card-title">Rekening Transfer Infak Pembangunan Masjid</h2>
                            <span class="badge bg-success">Terbaru</span>
                        </div>
                        <div class="row text-center justify-content-center">
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="bi bi-credit-card-2-back-fill text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">3115-01-040846-53-7</h3>
                                <p class="text-muted">Rekening Bank BRI</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="bi bi-credit-card-2-back-fill text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">180-00-10171173</h3>
                                <p class="text-muted">Rekening Mandiri</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card-icon mx-auto mb-3">
                                    <i class="bi bi-credit-card-2-back-fill text-white fs-3"></i>
                                </div>
                                <h3 class="fw-bold text-success">2-012-24515-0</h3>
                                <p class="text-muted">Rekening Bank Jateng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container footer-content">
            <div class="row g-4">
                <div class="col-lg-4 text-center text-lg-start">

                    <h5 class="fw-bold mb-3" style="color: var(--accent-gold);">Masjid Jami' Al Munawwarah</h5>
                    <p class="mb-4">Bersama membangun masjid untuk kebaikan umat dan masyarakat sekitar. Mengelola amanah dana infak dan zakat dengan transparansi.</p>

                </div>

                <div class="col-lg-4 footer-section">
                    <h6><i class="fas fa-map-marker-alt me-2"></i>Informasi Kontak</h6>
                    <p><i class="fas fa-map-marker-alt me-3"></i>Jl. Raya Maoslor Kec. Maos,<br>Kab. Cilacap, Jawa Tengah</p>
                    <p><i class="fas fa-phone me-3"></i>+62 857 4287 5424</p>
                    <p><i class="fas fa-phone me-3"></i>+62 856 9377 2077</p>
                </div>

                <div class="col-lg-4 footer-section">
                    <h6><i class="fas fa-clock me-2"></i>Jadwal Operasional</h6>
                    <div class="mb-3">
                        <p class="mb-1"><strong>Senin - Minggu</strong></p>
                        <p>03:00 - 22:00 WIB</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1"><strong>Hari Raya</strong></p>
                        <p>24 Jam (Buka Sepanjang Hari)</p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1"><strong>Jadwal Sholat</strong></p>
                        <p>Sesuai waktu setempat</p>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; 2025 Masjid Jami' Al Munawwarah. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">Powered by <span style="color: var(--accent-gold);">SIPENDAIZ</span></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>

</html>