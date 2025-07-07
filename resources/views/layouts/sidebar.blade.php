<!-- Sidebar -->
<aside id="sidebar" class=" vh-100 sidebar-expanded position-fixed overflow-auto">

    <div class="p-3 overflow-auto" style="margin-bottom:80px;">



        <ul class="nav flex-column">

            <!-- Dashboard  -->
            @if(in_array(auth()->user()->role, ['Bendahara', 'Petugas', 'Ketua']))
            <li class="nav-item">
                <a href="{{ route('dashboard.index') }}" class="nav-link d-flex align-items-center">
                    <i class="bi bi-house-door-fill me-2"></i> <span>Dashboard</span>
                </a>
            </li>
            @endif


            <!-- INFAK  -->
            @if(in_array(auth()->user()->role, ['Bendahara', 'Petugas', 'Ketua']))
            <li class="text-muted small mt-4">INFAK</li>

            <!-- BUKTI TRANSAKSI PEMASUKAN  -->
            @if(in_array(auth()->user()->role, ['Bendahara', 'Petugas']))
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse"
                    href="#buktiSubmenu" role="button" aria-expanded="false" aria-controls="buktiSubmenu">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-receipt me-2"></i> <span>Bukti Transaksi Pemasukan</span>
                    </div>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </a>

                <div class="collapse" id="buktiSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li>
                            <a href="{{ route('bukti_transaksi.index') }}" class="nav-link d-flex align-items-center mt-1">
                                <i class="bi bi-circle me-2"></i><span>Bukti Transaksi</span>
                            </a>
                        </li>
                        @if(auth()->user()->role == 'Bendahara')
                        <li>
                            <a href="{{ route('bukti_transaksi.konfirmasi') }}" class="nav-link d-flex align-items-center">
                                <i class="bi bi-circle me-2"></i><span>Konfirmasi Transaksi</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif


            <!-- Pembangunan  -->
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse"
                    href="#pembangunanSubmenu" role="button" aria-expanded="false" aria-controls="pembangunanSubmenu">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-mosque me-2"></i> <span>Pembangunan</span>
                    </div>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </a>

                <div class="collapse" id="pembangunanSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li>
                            <a href="{{ route('infak_masuk.index', ['kategori' => 'Pembangunan']) }}"
                                class="nav-link d-flex align-items-center mt-1">
                                <i class="bi bi-circle me-2"></i><span>Pemasukan</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('infak_keluar.index', ['kategori' => 'Pembangunan']) }}"
                                class="nav-link d-flex align-items-center">
                                <i class="bi bi-circle me-2"></i><span>Pengeluaran</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Takmir -->
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse"
                    href="#takmirSubmenu" role="button" aria-expanded="false" aria-controls="takmirSubmenu">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-people-group me-2"></i> <span>Takmir</span>
                    </div>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </a>

                <div class="collapse" id="takmirSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li>
                            <a href="{{ route('infak_masuk.index', ['kategori' => 'Takmir']) }}"
                                class="nav-link d-flex align-items-center mt-1">
                                <i class="bi bi-circle me-2"></i><span>Pemasukan</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('infak_keluar.index', ['kategori' => 'Takmir']) }}"
                                class="nav-link d-flex align-items-center">
                                <i class="bi bi-circle me-2"></i><span>Pengeluaran</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- ZAKAT  -->
            @if(in_array(auth()->user()->role, ['Bendahara', 'Petugas', 'Ketua']))
            <li class="text-muted small mt-4">ZAKAT</li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse"
                    href="#zakatSubmenu" role="button" aria-expanded="false" aria-controls="zakatSubmenu">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-box2-fill me-2"></i> <span>Zakat</span>
                    </div>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </a>

                <div class="collapse" id="zakatSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li>
                            <a href="{{ route('zakat_masuk.index') }}" class="nav-link d-flex align-items-center mt-1">
                                <i class="bi bi-circle me-2"></i><span>Pemasukan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('zakat_keluar.index') }}" class="nav-link d-flex align-items-center">
                                <i class="bi bi-circle me-2"></i><span>Pengeluaran</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            <!-- MUSTAHIK (tidak untuk bendahara) -->
            @if(auth()->user()->role == 'Petugas')

            <li class="nav-item">
                <a href="{{ route('mustahik.index') }}" class="nav-link d-flex align-items-center">
                    <i class="bi bi-people-fill me-2"></i> <span>Mustahik</span>
                </a>
            </li>
            @endif

            <!-- LAPORAN (bendahara dan ketua) -->

            <li class="text-muted small mt-4">LAPORAN</li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center justify-content-between" data-bs-toggle="collapse"
                    href="#infakSubmenu" role="button" aria-expanded="false" aria-controls="infakSubmenu">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-archive-fill me-2"></i> <span>Infak</span>
                    </div>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </a>

                <div class="collapse" id="infakSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li>
                            <a href="{{ route('laporan_infak.index', ['kategori' => 'pembangunan']) }}"
                                class="nav-link d-flex align-items-center mt-1">
                                <i class="bi bi-circle me-2"></i><span>Pembangunan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('laporan_infak.index', ['kategori' => 'takmir']) }}"
                                class="nav-link d-flex align-items-center">
                                <i class="bi bi-circle me-2"></i><span>Takmir</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="{{ route('laporan_zakat.index') }}" class="nav-link d-flex align-items-center">
                    <i class="bi bi-archive-fill me-2"></i> <span>Zakat</span>
                </a>
            </li>



            <!-- USERS (hanya bendahara)  -->
            <li class="text-muted small mt-4">USERS</li>
            @if(auth()->user()->role == 'Bendahara')

            <li class="nav-item">
                <a href="{{ route('user.index') }}" class="nav-link d-flex align-items-center">
                    <i class="fas fa-user-plus me-2"></i> <span>User</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('pejabat.index') }}" class="nav-link d-flex align-items-center">
                    <i class="fas fa-user-plus me-2"></i> <span>Pengurus</span>
                </a>
            </li>
            @endif

            <!-- PROFIL (semua bisa akses) -->
            <li class="nav-item">
                <a href="{{ route('profil.index') }}" class="nav-link d-flex align-items-center">
                    <i class="bi bi-person-circle me-2"></i> <span>Profil</span>
                </a>
            </li>

            <!-- LOGOUT (semua bisa akses) -->
            <li id="logout-btn" class="nav-item">
                <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                    @csrf
                    <a href="#" class="nav-link d-flex align-items-center" onclick="konfirmasiLogoutSidebar(event)">
                        <i class="bi bi-box-arrow-right me-2"></i> <span>Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</aside>

<script>
    function konfirmasiLogoutSidebar(event) {
        event.preventDefault(); // cegah aksi default <a href="#">
        Swal.fire({
            title: "Keluar dari akun?",
            text: "Apakah Anda yakin ingin logout?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, logout",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("logout-form-sidebar").submit();
            }
        });
    }
</script>