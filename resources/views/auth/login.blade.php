<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'figtree', sans-serif;
            background-color: #f8f9fa;
        }
        .login-container {
            display: flex;
            min-height: 100vh;
        }
        .left-side {
            flex: 1;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .right-side {
            flex: 1;
            background-color: #f0f7ff;
            background-image: url('{{ asset('images/gradasi.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .logo-container img {
            height: 60px;
            margin-right: 10px;
        }
        .login-text {
            color: #0d6efd;
            font-weight: bold;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            margin-bottom: 1rem;
        }
        .btn-masuk {
            background: linear-gradient(to right, #e67e22, #d35400);
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            color: white;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
        }
        .illustration-img {
            max-width: 80%;
            margin: 0 auto;
            display: block;
            position: relative;
            z-index: 2;
        }
        .tagline {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .sub-tagline {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }
        .forgot-password {
            text-align: right;
            font-size: 0.9rem;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .tagline {
                font-size: 1.3rem;
            }
            .login-text {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .left-side, .right-side {
                flex: none;
                width: 100%;
            }
            .right-side {
                order: -1;
                padding: 1.5rem;
            }
            .illustration-img {
                max-width: 60%;
                margin-bottom: 1rem;
            }
            .tagline {
                font-size: 1.2rem;
                text-align: center;
            }
            .sub-tagline {
                text-align: center;
            }
            .login-text {
                font-size: 1.5rem;
                text-align: center;
            }
        }
        
        @media (max-width: 576px) {
            .left-side, .right-side {
                padding: 1rem;
            }
            .logo-container {
                justify-content: center;
            }
            .logo-container img {
                height: 50px;
            }
            .illustration-img {
                max-width: 80%;
            }
            .tagline {
                font-size: 1.1rem;
            }
            .login-text {
                font-size: 1.3rem;
            }
            .form-control {
                padding: 0.5rem 0.8rem;
            }
            .btn-masuk {
                padding: 0.5rem 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="left-side">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" onerror="this; this.onerror=null;">
                <div>
                    <div class="fw-bold">PEMERINTAH KOTA BUKITTINGGI</div>
                    <div class="small">Provinsi Sumatera Barat</div>
                </div>
            </div>
            
            <h2 class="tagline">Selangkah Lebih Dekat Dengan Suksesmu</h2>
            <p class="sub-tagline">Daftarkan Dirimu Untuk Memulai Magang/Penelitian/Kerja di Diskominfo</p>
            
            <h3 class="login-text">Masukkan Akunmu!</h3>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-3">
                        {{ session('status') }}
                    </div>
                @endif
                
                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a class="small text-decoration-none" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>
                
                <button type="submit" class="btn-masuk">Masuk</button>
                
                <div class="text-center mt-3">
                    <span class="small">Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar disini</a></span>
                </div>
            </form>
        </div>
        
        <div class="right-side">
            <img src="{{ asset('images/image login.png') }}" alt="Illustration" class="illustration-img" onerror="this.src='https://img.freepik.com/free-vector/computer-login-concept-illustration_114360-7962.jpg'; this.onerror=null;">
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
