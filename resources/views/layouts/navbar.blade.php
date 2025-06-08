<nav class="navbar navbar-expand-lg navbar-light  fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2 text-white text-decoration-none" href="#">
            <i class="fas fa-mosque fs-4"></i>
            <span class="fw-bold fs-4">SIPENDAIZ</span>
        </a>

        <button class="btn me-2 text-white" type="button" id="sidebarToggle" style="position: absolute; left: 220px;">
            <i class="bi bi-list" style="font-size: 1.5rem;"></i>
        </button>
        <div class="d-flex align-items-center ms-auto">
            <!-- <a href="{{ route('bukti_transaksi.konfirmasi') }}" class="btn position-relative me-1">
                <i class="bi bi-bell fs-5 text-white"></i>
                @if($jumlahPending > 0)
                <span class="position-absolute top-50 start-20 translate-middle badge rounded-pill bg-danger">
                    {{ $jumlahPending }}
                </span>
                @endif
            </a> -->

            <!-- <div class="dropdown"> -->
            <a href="#" class="d-flex align-items-center text-decoration-none text-white" aria-expanded="false">
                <i class="bi bi-person-fill bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-2"
                    style="width: 30px; height: 30px; font-size: 18px;"></i>
                <strong>{{ auth()->user()->nama }}</strong>
            </a>
            <!-- <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <button type="button" class="dropdown-item" onclick="konfirmasiLogout()">Logout</button>
                    </li>
                </ul> -->
            <!-- </div> -->
        </div>
    </div>
</nav>
<script>
    function konfirmasiLogout() {
        Swal.fire({
            title: "Keluar dari akun?",
            text: "Apakah Anda yakin ingin logout?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, logout",
            cancelButtonText: "Batal",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("logout-form").submit();
            }
        });
    }
</script>