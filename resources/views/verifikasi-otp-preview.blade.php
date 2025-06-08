<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ganti Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-green: #2d7d32;
            --dark-green: #1b5e20;
        }

        body {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .password-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 10px solid rgb(97, 177, 127);
            max-width: 480px;
            padding: 3rem 2.5rem;
        }

        .password-icon {
            background: linear-gradient(45deg, rgb(77, 197, 85), #1b5e20);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            color: white;
            font-size: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 6px 18px rgba(37, 211, 102, 0.3);
        }

        .password-title {
            text-align: center;
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .password-pattern {
            width: 100%;
            background: repeating-linear-gradient(45deg,
                    transparent,
                    transparent 10px,
                    rgba(255, 255, 255, 0.03) 10px,
                    rgba(255, 255, 255, 0.03) 20px);
            animation: subtle-move 20s linear infinite;
        }

        .form-floating>.form-control:focus {
            border-color: #25D366;
            box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, rgb(77, 197, 85), #1b5e20);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.75rem 1.5rem;
            width: 100%;
        }

        .input-group-text {
            background: linear-gradient(45deg, rgb(77, 197, 85), #1b5e20);
            color: white;
            border: none;
            border-radius: 12px 0 0 12px;
        }

        .password-input .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }

        label {
            color: #2c3e50;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 password-pattern">
        <div class="password-container shadow-lg p-5 w-100" style="max-width: 480px;">
            <div class="text-center mb-4">
                <div class="password-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h2 class="password-title">Ganti Password</h2>
            </div>

            <form action="{{ route('password.ganti_password') }}" method="POST" id="passwordForm" novalidate>
                @csrf
                <input type="hidden" id="id_user" name="id_user" value="{{ 'id_user' }}" />

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>
                        <div class="form-floating password-input flex-grow-1">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password Baru" required autofocus>
                            <label for="password">Password Baru</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>
                        <div class="form-floating password-input flex-grow-1">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                                placeholder="Konfirmasi Password Baru" required autofocus>
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>
                    Simpan
                    <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>
        </div>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value.trim();
        const passwordConfirmation = document.getElementById('password_confirmation').value.trim();

        if (!password) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Password kosong!',
                text: 'Silakan isi password baru Anda terlebih dahulu.',
                confirmButtonColor: '#2d7d32'
            });
            return;
        }

        if (!passwordConfirmation) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Konfirmasi password kosong!',
                text: 'Silakan isi konfirmasi password baru Anda.',
                confirmButtonColor: '#2d7d32'
            });
            return;
        }

        if (password !== passwordConfirmation) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password tidak cocok!',
                text: 'Password dan konfirmasi password harus sama.',
                confirmButtonColor: '#d32f2f'
            });
            return;
        }

        // Jika validasi lolos, tampilkan loading SweetAlert
        e.preventDefault();

        Swal.fire({
            title: 'Memverifikasi...',
            text: 'Mohon tunggu sebentar',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            e.target.submit();
        }, 1000);
    });
</script>