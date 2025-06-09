<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2d7d32;
            --dark-green: #1b5e20;
        }

        body {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .otp-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 10px solid rgb(97, 177, 127);
        }

        .otp-icon {
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

        .otp-title {
            text-align: center;
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .card-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .form-floating>.form-control:focus {
            border-color: #25D366;
            box-shadow: 0 0 0 0.2rem rgba(37, 211, 102, 0.25);
        }

        .otp-pattern {
            width: 100%;
            background: repeating-linear-gradient(45deg,
                    transparent,
                    transparent 10px,
                    rgba(255, 255, 255, 0.03) 10px,
                    rgba(255, 255, 255, 0.03) 20px);
            animation: subtle-move 20s linear infinite;
        }

        .input-group-text {
            background: linear-gradient(45deg, rgb(77, 197, 85), #1b5e20);
            color: white;
            border: none;
            border-radius: 12px 0 0 12px;
        }

        .otp-input .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
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

        .btn-danger {
            margin-top: 10px;
            border-radius: 12px;
            width: 100%;
            font-weight: 600;
        }

        .security-badge {
            background: rgba(37, 211, 102, 0.1);
            color: #25D366;
            border: 1px solid rgba(37, 211, 102, 0.2);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="col-lg-6 d-flex align-items-center justify-content-center min-vh-100 otp-pattern">
        <div class="otp-container shadow-lg p-5 w-100" style="max-width: 480px;">
            <div class="text-center mb-4">
                <div class="otp-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h2 class="otp-title">Verifikasi OTP</h2>
                <p class="card-subtitle">Masukkan kode OTP Anda untuk melanjutkan proses verifikasi</p>
            </div>

            <form action="{{ route('password.verifikasi_otp') }}" id="otpForm" method="POST" novalidate>
                @csrf
                <input type="hidden" id="id_user" name="id_user" value="{{ session('id_user') }}">

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>

                        <input type="hidden" class="form-control" id="telepon" name="telepon"
                            placeholder="Contoh: 08123456789" value="{{ session('nomor_telepon') }}" required autofocus>

                        <div class="form-floating otp-input flex-grow-1">
                            <input type="text" class="form-control" id="otp" name="otp"
                                placeholder="Masukkan Kode OTP" required autofocus>
                            <label for="otp">Kode OTP</label>
                        </div>
                    </div>
                    <div class="form-text mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Kode OTP 6 digit
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>
                    Verifikasi
                    <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>
            <form action="{{ route('password.kirim_ulang_otp') }}" method="POST" id="resendOtpForm">
                @csrf
                <input type="hidden" name="id_user" value="{{session('id_user') }}">
                <button type="submit" class="btn btn-danger" id="resendOtpBtn">
                    <i class="fas fa-redo me-2"></i>Kirim Ulang OTP
                </button>
            </form>
            <!-- Tombol Kembali -->
            <a href="{{ route('password.telepon') }}" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
            <div class="security-badge text-center">
                <i class="fas fa-shield-alt me-2"></i>Kode OTP Anda bersifat rahasia
            </div>
        </div>
    </div>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('otpForm').addEventListener('submit', function(e) {
        const otp = document.getElementById('otp').value.trim();

        if (!otp) {
            e.preventDefault(); // Mencegah form terkirim
            Swal.fire({
                icon: 'warning',
                title: 'Kode OTP kosong!',
                text: 'Silakan isi kode OTP Anda terlebih dahulu.',
                confirmButtonColor: '#2d7d32',
                confirmButtonText: 'OK'
            });
        } else if (otp.length !== 6 || !/^\d+$/.test(otp)) {
            e.preventDefault(); // Mencegah form terkirim
            Swal.fire({
                icon: 'warning',
                title: 'Format OTP tidak valid!',
                text: 'Kode OTP harus terdiri dari 6 digit angka.',
                confirmButtonColor: '#2d7d32',
                confirmButtonText: 'OK'
            });
        } else {
            // Tampilkan loading SweetAlert sebelum form dikirim
            e.preventDefault(); // Tahan dulu pengiriman

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

            // Lanjutkan submit form setelah 1 detik
            setTimeout(() => {
                e.target.submit();
            }, 1000);
        }
    });
</script>

<!-- kirim otp setiap 10 menit -->
<script>
    const resendBtn = document.getElementById('resendOtpBtn');
    const RESEND_INTERVAL = 10 * 60 * 1000; // 10 menit dalam milidetik

    function updateResendButtonState() {
        const lastClicked = localStorage.getItem('lastResendOtpTime');
        if (lastClicked) {
            const now = Date.now();
            const diff = now - parseInt(lastClicked);

            if (diff < RESEND_INTERVAL) {
                disableResendButton(RESEND_INTERVAL - diff);
            } else {
                enableResendButton();
            }
        }
    }

    function disableResendButton(timeRemaining) {
        resendBtn.disabled = true;
        let countdown = Math.floor(timeRemaining / 1000);
        resendBtn.innerHTML = `<i class="fas fa-hourglass-half me-2"></i> Coba lagi dalam ${formatTime(countdown)}`;

        const interval = setInterval(() => {
            countdown--;
            resendBtn.innerHTML = `<i class="fas fa-hourglass-half me-2"></i> Coba lagi dalam ${formatTime(countdown)}`;

            if (countdown <= 0) {
                clearInterval(interval);
                enableResendButton();
            }
        }, 1000);
    }

    function enableResendButton() {
        resendBtn.disabled = false;
        resendBtn.innerHTML = `<i class="fas fa-redo me-2"></i> Kirim Ulang OTP`;
    }

    function formatTime(seconds) {
        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
        const s = (seconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    // Saat tombol diklik, simpan waktu ke localStorage
    document.getElementById('resendOtpForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah form terkirim langsung
        
        Swal.fire({
            title: 'Mengirim ulang OTP...',
            text: 'Mohon tunggu sebentar',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        localStorage.setItem('lastResendOtpTime', Date.now());
        
        // Submit form setelah delay
        setTimeout(() => {
            e.target.submit();
        }, 1000);
    });

    // Jalankan saat halaman dimuat
    updateResendButtonState();
</script>

<!-- Notifikasi Sukses -->
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
    });
</script>
@endif

<!-- Notifikasi Error -->
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Kode OTP Salah!',
        text: '{{ session("error") }}',
        showConfirmButton: true,
        confirmButtonText: 'Coba Lagi',
        confirmButtonColor: '#dc3545',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Focus kembali ke input OTP setelah user menutup alert
            document.getElementById('otp').focus();
            document.getElementById('otp').select();
        }
    });
</script>
@endif

