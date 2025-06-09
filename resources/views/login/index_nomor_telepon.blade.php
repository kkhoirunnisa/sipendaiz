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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 10px solid rgb(97, 177, 127);
        }

        .icon-container {
            background: linear-gradient(45deg, rgb(77, 197, 85), #1b5e20);
            box-shadow: 0 8px 25px rgba(41, 127, 72, 0.3);
            width: 80px;
            height: 80px;
        }

        .form-floating>.form-control:focus {
            border-color: #25D366;
            box-shadow: 0 0 0 0.25rem rgba(37, 211, 102, 0.15);
            background: white;
        }

        .form-floating>label {
            color: #6c757d;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(45deg, rgb(77, 197, 85), #1b5e20);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
            letter-spacing: 0.5px;
        }

        .card-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .whatsapp-pattern {
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

        .phone-input .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
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
    <div class="col-lg-6 d-flex align-items-center justify-content-center min-vh-100 whatsapp-pattern">
        <div class="card form-container shadow-lg p-5 w-100" style="max-width: 480px;">
            <div class="text-center mb-4">
                <div class="icon-container rounded-circle text-white d-inline-flex align-items-center justify-content-center">
                    <i class="fab fa-whatsapp fa-2x"></i>
                </div>
                <h4 class="card-title mt-4">Verifikasi WhatsApp</h4>
                <p class="card-subtitle">Masukkan nomor WhatsApp Anda untuk melanjutkan proses verifikasi</p>
            </div>

            <form action="{{ route('password.kirim_otp') }}" method="POST" id="whatsappForm" novalidate>
                @csrf

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>
                        <div class="form-floating phone-input flex-grow-1">
                            <input type="tel" class="form-control" id="telepon" name="telepon"
                                placeholder="Contoh: 08123456789" required autofocus>
                            <label for="telepon">Nomor WhatsApp</label>
                        </div>
                    </div>
                    <div class="form-text mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Format: 08xxxxxxxxxx
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-paper-plane me-2"></i>
                    Kirim Kode Verifikasi
                    <i class="fas fa-arrow-right ms-2"></i>
                </button>
                <!-- Tombol Kembali -->
                <a href="{{ route('login.index_login') }}" class="btn btn-primary w-100 mb-3 mt-1">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
                <div class="security-badge text-center">
                    <i class="fas fa-shield-alt me-2"></i>
                    Nomor Anda akan dijaga kerahasiaannya
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('whatsappForm').addEventListener('submit', function(e) {
        const telepon = document.getElementById('telepon').value.trim();

        if (!telepon) {
            e.preventDefault(); // Mencegah form terkirim

            Swal.fire({
                icon: 'warning',
                title: 'Nomor WhatsApp kosong!',
                text: 'Silakan isi nomor WhatsApp Anda terlebih dahulu.',
                confirmButtonColor: '#2d7d32',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('telepon').focus();
                }
            });
        } else if (!isValidPhoneNumber(telepon)) {
            e.preventDefault(); // Mencegah form terkirim

            Swal.fire({
                icon: 'warning',
                title: 'Format nomor tidak valid!',
                text: 'Nomor WhatsApp harus dimulai dengan 08 dan minimal 10 digit.',
                confirmButtonColor: '#2d7d32',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('telepon').focus();
                    document.getElementById('telepon').select();
                }
            });
        } else {
            // Tampilkan loading SweetAlert sebelum form dikirim
            e.preventDefault(); // Tahan dulu pengiriman

            Swal.fire({
                title: 'Memverifikasi nomor...',
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

    // Fungsi validasi format nomor telepon
    function isValidPhoneNumber(phone) {
        // Hapus spasi dan karakter non-digit
        const cleanPhone = phone.replace(/\D/g, '');

        // Cek apakah dimulai dengan 08 dan panjang minimal 10 digit
        return /^08\d{8,}$/.test(cleanPhone);
    }

    // Format input nomor telepon secara real-time
    document.getElementById('telepon').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Hapus semua karakter non-digit

        // Batasi panjang maksimal 13 digit
        if (value.length > 13) {
            value = value.substring(0, 13);
        }

        e.target.value = value;
    });
</script>



<!-- Notifikasi Error dari Controller -->
@if ($errors->has('telepon'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Nomor WhatsApp Tidak Terdaftar!',
        text: '{{ $errors->first("telepon") }}',
        showConfirmButton: true,
        confirmButtonText: 'Coba Lagi',
        confirmButtonColor: '#dc3545',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Focus kembali ke input telepon setelah user menutup alert
            document.getElementById('telepon').focus();
            document.getElementById('telepon').select();
        }
    });
</script>
@endif

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