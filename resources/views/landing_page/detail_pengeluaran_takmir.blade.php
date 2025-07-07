<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPENDAIZ</title>
    <link rel="icon" href="/gambar/logo1.png" type="image/png">
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

        /* .logo-container:hover {
            transform: scale(1.1) rotate(5deg);
        } */

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

        .table-gradient {
            background-image: var(--gradient-gold);
        }

        .table-gradient th {
            background: transparent;
        }
    </style>
    <!-- navigation -->

<body>
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container"><a class="navbar-brand d-none d-md-flex" href="#">
                <div class="logo-container"><i class="fas fa-mosque text-white fs-4"></i></div>
                <div class="logo-text">
                    <h1>Masjid Jami' Al Munawwarah</h1>
                </div>
            </a><a href="/login" class="btn-login"><i class="fas fa-sign-in-alt me-2"></i>Login </a></div>
    </nav>
    <main class="container" style="padding-top: 100px; padding-bottom: 100px;">
        <div class="container">
            <h4 class="mb-4 fw-bold text-dark">Detail Pengeluaran Infak Takmir</h4>

            <a href="{{url('/')}}" class="btn btn-success mt-0 mb-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>

            <div class="table-responsive border shadow rounded-4">
                <table class="table table-bordered table-hover align-items-center mb-0" style="min-width: 800px;">
                    <thead class="table-gradient text-white">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Nominal (Rp)</th>
                            <th class="text-center">Barang</th>
                            <th class="text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluaranTakmir as $index => $item)
                        <tr>
                            <td class="text-center fw-semibold text-muted">
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    {{ $pengeluaranTakmir->firstItem() + $index }}
                                </span>
                            </td>
                            <td class="text-center">{{ ucfirst($item->kategori) }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="text-center">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->barang }}</td>
                            <td class="text-center">{{ $item->keterangan }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pengeluaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Info dan Navigasi --}}
            @if ($pengeluaranTakmir->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap px-2">
                <div class="d-flex align-items-center">
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        <i class="bi bi-info-circle me-1"></i>
                        Menampilkan {{ $pengeluaranTakmir->firstItem() }} - {{ $pengeluaranTakmir->lastItem() }} dari {{ $pengeluaranTakmir->total() }} data
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-normal">
                        @if ($pengeluaranTakmir->onFirstPage())
                        <span class="btn-sm text-white opacity-50"><i class="bi bi-chevron-left"></i> Previous</span>
                        @else
                        <a href="{{ $pengeluaranTakmir->previousPageUrl() }}" class="btn-sm text-white">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                        @endif

                        <span class="mx-2">Page {{ $pengeluaranTakmir->currentPage() }} of {{ $pengeluaranTakmir->lastPage() }}</span>

                        @if ($pengeluaranTakmir->hasMorePages())
                        <a href="{{ $pengeluaranTakmir->nextPageUrl() }}" class="btn-sm text-white">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                        @else
                        <span class="btn-sm text-white opacity-50">Next <i class="bi bi-chevron-right"></i></span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

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


</body>

</html>