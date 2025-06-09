<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPENDAIZ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.10.8/autoNumeric.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- boostrap modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    

</head>

<body>
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div id="main-content" class="container-fluid mt-3">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const body = document.body;

            // Create backdrop element for mobile
            const backdrop = document.createElement('div');
            backdrop.className = 'sidebar-backdrop';
            document.body.appendChild(backdrop);

            // Function to check if device is mobile
            function isMobile() {
                return window.innerWidth <= 767;
            }

            // Function to handle sidebar toggle
            function toggleSidebar() {
                const isCurrentlyExpanded = sidebar.classList.contains('sidebar-expanded');

                if (isMobile()) {
                    // Mobile behavior - overlay
                    if (isCurrentlyExpanded) {
                        // Close sidebar
                        sidebar.classList.remove('sidebar-expanded');
                        body.classList.remove('sidebar-visible', 'sidebar-open');
                    } else {
                        // Open sidebar
                        sidebar.classList.add('sidebar-expanded');
                        body.classList.add('sidebar-visible', 'sidebar-open');
                    }
                } else {
                    // Desktop behavior - push content
                    if (isCurrentlyExpanded) {
                        // Collapse sidebar
                        sidebar.classList.remove('sidebar-expanded');
                        sidebar.classList.add('sidebar-collapsed');
                        body.classList.remove('sidebar-visible');
                        mainContent.classList.add('full-width');
                    } else {
                        // Expand sidebar
                        sidebar.classList.add('sidebar-expanded');
                        sidebar.classList.remove('sidebar-collapsed');
                        body.classList.add('sidebar-visible');
                        mainContent.classList.remove('full-width');
                    }
                }
            }

            // Sidebar toggle handler
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            // Close sidebar when clicking backdrop (mobile only)
            backdrop.addEventListener('click', function() {
                if (isMobile() && sidebar.classList.contains('sidebar-expanded')) {
                    toggleSidebar();
                }
            });

            // Handle window resize to adjust sidebar behavior
            window.addEventListener('resize', function() {
                // Reset sidebar state when switching between mobile and desktop
                if (isMobile()) {
                    // Mobile: ensure sidebar is hidden by default
                    sidebar.classList.remove('sidebar-collapsed');
                    mainContent.classList.remove('full-width');
                    if (!sidebar.classList.contains('sidebar-expanded')) {
                        body.classList.remove('sidebar-visible', 'sidebar-open');
                    }
                } else {
                    // Desktop: reset to default expanded state
                    sidebar.classList.add('sidebar-expanded');
                    sidebar.classList.remove('sidebar-collapsed');
                    body.classList.add('sidebar-visible');
                    body.classList.remove('sidebar-open');
                    mainContent.classList.remove('full-width');
                }
            });

            // Close sidebar when clicking nav links (mobile only)
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    // Don't close if it's a collapse toggle
                    if (!this.hasAttribute('data-bs-toggle')) {
                        if (isMobile() && sidebar.classList.contains('sidebar-expanded')) {
                            setTimeout(() => {
                                toggleSidebar();
                            }, 150); // Small delay to allow navigation
                        }
                    }
                });
            });

            // Restore active nav link from localStorage
            const activeLinkHref = localStorage.getItem('activeNavLink');

            if (activeLinkHref) {
                const activeLink = document.querySelector(`.nav-link[href="${activeLinkHref}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');

                    // Buka collapse parent kalau ada
                    const parentCollapse = activeLink.closest('.collapse');
                    if (parentCollapse) {
                        // Buka submenu
                        const bsCollapse = new bootstrap.Collapse(parentCollapse, {
                            show: true
                        });

                        // Tandai link parent menu utama sebagai active
                        const parentMenuLink = parentCollapse.previousElementSibling;
                        if (parentMenuLink && parentMenuLink.classList.contains('nav-link')) {
                            parentMenuLink.classList.add('active');

                            // Ubah ikon pada parent menu menjadi up
                            const icon = parentMenuLink.querySelector('.toggle-icon');
                            if (icon) {
                                icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                            }
                        }
                    }
                }
            }

            // Active link click event
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    localStorage.setItem('activeNavLink', this.getAttribute('href'));

                    const logoutBtn = document.getElementById('logout-btn');

                    logoutBtn.addEventListener('click', function() {
                        localStorage.setItem('activeNavLink', window.location.origin + '/dashboard');
                    })
                });
            });

            // Collapse icon toggle
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(link => {
                const icon = link.querySelector('.toggle-icon');
                const targetId = link.getAttribute('href');
                const collapseTarget = document.querySelector(targetId);

                if (collapseTarget && icon) {
                    // Saat submenu dibuka
                    collapseTarget.addEventListener('show.bs.collapse', () => {
                        icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                    });

                    // Saat submenu ditutup
                    collapseTarget.addEventListener('hide.bs.collapse', () => {
                        icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
                    });

                    // Set ikon awal saat load halaman
                    if (collapseTarget.classList.contains('show')) {
                        icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                    }

                    // Event listener untuk menandai menu sebagai aktif saat diklik
                    link.addEventListener('click', () => {
                        // Cek apakah collapse sedang terbuka
                        const isExpanded = link.getAttribute('aria-expanded') === 'true';

                        // Jika belum terbuka (akan terbuka setelah klik), ubah ikon
                        if (!isExpanded) {
                            icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                        }
                    });
                }
            });

            // Tambahkan event listener untuk submenu links
            document.querySelectorAll('.collapse .nav-link').forEach(subLink => {
                subLink.addEventListener('click', () => {
                    // Dapatkan parent collapse
                    const parentCollapse = subLink.closest('.collapse');
                    if (parentCollapse) {
                        // Dapatkan tombol toggle parent
                        const parentToggle = parentCollapse.previousElementSibling;
                        if (parentToggle) {
                            // Ubah ikon pada parent
                            const icon = parentToggle.querySelector('.toggle-icon');
                            if (icon) {
                                icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                            }
                        }
                    }
                });
            });

            // Initialize sidebar state based on screen size
            if (isMobile()) {
                sidebar.classList.remove('sidebar-expanded');
                body.classList.remove('sidebar-visible', 'sidebar-open');
            } else {
                sidebar.classList.add('sidebar-expanded');
                body.classList.add('sidebar-visible');
            }
        });
    </script>
</body>

</html>