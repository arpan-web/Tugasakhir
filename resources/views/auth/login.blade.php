<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Poliklinik Polnep</title>
    <!-- MENGGUNAKAN BOOTSTRAP 5 VIA CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgba(216, 241, 250, 0.46) 0%, rgba(248, 250, 252, 1) 90.1%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        /* Decorative Blob 1 */
        .blob-1 {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.15) 0%, rgba(96, 165, 250, 0) 70%);
            top: -100px;
            left: -100px;
            z-index: 1;
        }

        /* Decorative Blob 2 */
        .blob-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0) 70%);
            bottom: -150px;
            right: -150px;
            z-index: 1;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            padding: 15px;
            display: flex;
            justify-content: center;
        }

        .login-card {
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 2.5rem 2.25rem;
            width: 100%;
            max-width: 420px;
            transition: transform 0.3s ease;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-icon-wrapper {
            width: 54px;
            height: 54px;
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.1);
        }

        .login-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .login-subtitle {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 4px;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 11px 14px;
            font-size: 0.9rem;
            color: #334155;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: 0;
        }

        .btn-polnep {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            transition: all 0.2s ease;
        }

        .btn-polnep:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
            color: white;
        }

        .btn-polnep:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 10px;
            font-size: 0.875rem;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        .invalid-feedback {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<div class="blob-1"></div>
<div class="blob-2"></div>

<div class="login-wrapper">
    <div class="login-card">
        <div class="logo-section">
            <img src="{{ asset('logo-polnep.png') }}" alt="Logo POLNEP" style="height: 80px; width: auto; margin-bottom: 16px; object-fit: contain;">
            <h4 class="login-title">Poliklinik POLNEP</h4>
            <p class="login-subtitle">Sistem Informasi Manajemen Pelayanan Medis</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                <i data-feather="alert-circle" class="me-2" style="width: 16px; height: 16px;"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
                @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password" required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-polnep w-100">
                Masuk ke Sistem <i data-feather="arrow-right" class="ms-1" style="width: 16px; height: 16px;"></i>
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    feather.replace();
</script>
</body>
</html>
