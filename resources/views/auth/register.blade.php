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
        .register-container {
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
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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
        .welcome-text {
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
        .btn-daftar {
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
        .checkbox-container {
            margin-top: 1rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .tagline {
                font-size: 1.3rem;
            }
            .welcome-text {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 768px) {
            .register-container {
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
            .welcome-text {
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
            .welcome-text {
                font-size: 1.3rem;
            }
            .form-control {
                padding: 0.5rem 0.8rem;
            }
            .btn-daftar {
                padding: 0.5rem 0.8rem;
            }
            .checkbox-container label {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="left-side">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Provinsi Riau" onerror="this.src='https://diskominfotik.riau.go.id/wp-content/uploads/2017/04/logo-kominfo.png'; this.onerror=null;">
                <div>
                    <div class="fw-bold">PEMERINTAH RIAU</div>
                    <div class="small">Provinsi Sumatera Barat</div>
                </div>
            </div>
            
            <h2 class="tagline">Selangkah Lebih Dekat Dengan Suksesmu</h2>
            <p class="sub-tagline">Daftarkan Dirimu Untuk Memulai Magang/Penelitian/Kerja di Diskominfo</p>
            
            <h3 class="welcome-text">Selamat Datang!</h3>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                </div>
                
                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>
                
                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                </div>
                
                <div class="checkbox-container">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label small" for="terms">
                            Dengan ini saya menyetujui ketentuan syarat dan kebijakan privasi
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn-daftar">Daftar</button>
                
                <div class="mt-3 text-center">
                    <p class="small">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Masuk</a></p>
                </div>
            </form>
        </div>
        <div class="right-side">
            <img src="{{ asset('images/masuk.png') }}" alt="Illustration" class="illustration-img" onerror="this.src='https://img.freepik.com/free-vector/sign-up-concept-illustration_114360-7885.jpg'; this.onerror=null;">
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
