<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Masjid Jami' Al Munawwarah - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <style>
        :root {
            --primary-green: #2d7d32;
            --dark-green: #1b5e20;
            --light-green: #e8f5e8;
            --accent-gold: #ffd700;
            --text-dark: #2c3e50;
            --shadow-light: rgba(45, 125, 50, 0.1);
            --shadow-medium: rgba(45, 125, 50, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 50%, #a5d6a7 100%);
            height: 100vh;
            position: relative;
            overflow: hidden
        }

        /* body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(45, 125, 50, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(45, 125, 50, 0.05) 0%, transparent 50%);
            pointer-events: none;
        } */

        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border-radius: 0 50px 50px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(45deg,
                    transparent,
                    transparent 10px,
                    rgba(255, 255, 255, 0.03) 10px,
                    rgba(255, 255, 255, 0.03) 20px);
            animation: subtle-move 20s linear infinite;
        }

        @keyframes subtle-move {
            0% {
                transform: translateX(-100px) translateY(-100px);
            }

            100% {
                transform: translateX(100px) translateY(100px);
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 4rem 2rem;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, var(--accent-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            font-weight: 400;
            opacity: 0.95;
            line-height: 1.6;
        }

        .mosque-icon {
            font-size: 4rem;
            color: var(--accent-gold);
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .login-section {
            background: white;
            border-radius: 50px 0 0 50px;
            position: relative;
            overflow: hidden;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 3rem 2.5rem;
            margin: 2rem;
            box-shadow:
                0 20px 40px var(--shadow-light),
                0 10px 20px var(--shadow-medium),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(45, 125, 50, 0.1);
            position: relative;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-green), var(--accent-gold), var(--primary-green));
            border-radius: 25px 25px 0 0;
        }

        .login-title {
            color: var(--text-dark);
            font-weight: 600;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }

        .login-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-green), var(--accent-gold));
            border-radius: 2px;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(45, 125, 50, 0.25);
            background: white;
            transform: translateY(-2px);
        }

        .form-floating>label {
            color: #6c757d;
            font-weight: 500;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-green);
            z-index: 5;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
         
            border: none;
            border-radius: 15px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            color: white;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(45, 125, 50, 0.4);
            background: linear-gradient(135deg, var(--dark-green), #0d4f14);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            border-radius: 15px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
            background: linear-gradient(135deg, #c82333, #bd2130);
            color: white;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 20px var(--shadow-medium);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #6c757d;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
        }

        .divider span {
            padding: 0 1rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        @media (max-width: 991.98px) {
            .hero-section {
                border-radius: 0 0 50px 50px;
            }

            .login-section {
                border-radius: 50px 50px 0 0;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .login-container {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .login-container {
                margin: 0.5rem;
                padding: 1.5rem;
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid d-none d-md-flex main-container p-0">
        <div class="row w-100 g-0 min-vh-100">
            <!-- Hero Section -->
            <div class="col-lg-6 hero-section d-flex align-items-center">
                <div class="hero-content w-100 text-center fade-in">
                    <div class="mosque-icon">
                        <i class="fas fa-mosque"></i>
                    </div>
                    <h1 class="hero-title">SIPENDAIZ</h1>
                    <p class="hero-subtitle">
                        Masjid Jami' Al Munawwarah<br>
                        <strong>Sistem Informasi Pengelolaan Dana Infak dan Zakat</strong>
                    </p>
                    <div class="mt-4">
                        <i class="fas fa-hand-holding-heart me-2"></i>
                        <span>Mengelola Amanah dengan Transparansi</span>
                    </div>
                </div>
            </div>

            <!-- Login Section -->
            <div class="col-lg-6 login-section d-flex align-items-center justify-content-center">
                <div class="login-container fade-in w-100" style="max-width: 450px;">
                    <div class="logo-container">
                        <div class="logo-placeholder">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>

                    <h2 class="login-title">Selamat Datang</h2>

                    <form action="{{ route('login.index_login') }}" method="POST" novalidate>
                        @csrf

                        <div class="form-floating">
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Username" required autofocus>
                            <label for="username">
                                <i class="fas fa-user me-2"></i>Username
                            </label>

                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('password.telepon') }}" class="text-decoration-none text-muted">
                                <small><i class="fas fa-key me-1"></i>Lupa Password?</small>
                            </a>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Masuk
                        </button>
                    </form>

                    <div class="divider">
                        <span>atau</span>
                    </div>

                    <a href="{{ route('landing.index') }}" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>
                        Keluar
                    </a>


                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Hanya untuk Pengelola Masjid
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Notifikasi Sukses - Script ini akan menampilkan alert ketika password berhasil diubah -->
@if (session('success'))
<script>
    // Menampilkan alert sukses dengan animasi yang menarik
    Swal.fire({
        icon: 'success',
        title: 'Password Berhasil Diubah!',
        text: '{{ session("success") }}',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#2d7d32',
        timer: 4000,
        timerProgressBar: true,
        backdrop: `
                rgba(45, 125, 50, 0.1)
                left top
                no-repeat
            `,
        customClass: {
            popup: 'animated fadeInDown'
        }
    });
</script>
@endif

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->has('login'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: "{{ e($errors->first('login')) }}",
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#2d7d32',
        timer: 4000,
        timerProgressBar: true,
    });
</script>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle password visibility


    // Form validation feedback
    document.querySelector('form').addEventListener('submit', function(e) {
        const username = document.getElementById('username');
        const password = document.getElementById('password');
        let errorMsg = '';

        if (!username.value.trim()) {
            errorMsg = 'Username tidak boleh kosong!';
            username.classList.add('is-invalid');
        } else {
            username.classList.remove('is-invalid');
            username.classList.add('is-valid');
        }

        if (!password.value) {
            if (errorMsg) errorMsg += '\n';
            errorMsg += 'Password tidak boleh kosong!';
            password.classList.add('is-invalid');
        } else {
            password.classList.remove('is-invalid');
            password.classList.add('is-valid');
        }

        if (errorMsg) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Input Tidak Lengkap',
                html: errorMsg.replace(/\n/g, '<br>'),
                confirmButtonColor: '#2d7d32',
            });
        } 
    });
</script>