<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SIMADIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css') {{-- Tailwind CSS --}}
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-b from-white to-blue-100 font-sans">

    <div class="bg-white shadow-xl rounded-2xl flex w-full max-w-5xl overflow-hidden">
        {{-- Bagian Kiri: Form Login --}}
        <div class="w-full md:w-1/2 p-10">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 mb-6">
            <h2 class="text-2xl font-bold mb-1 text-blue-700">Masukkan Akunmu!</h2>
            <p class="text-gray-500 mb-6">Selangkah Lebih Dekat Dengan Suksesmu</p>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <input type="email" name="email" placeholder="Email" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">

                <input type="password" name="password" placeholder="Password" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded border-gray-300">
                        Ingat saya
                    </label>
                    <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Lupa password?</a>
                </div>

                @if ($errors->any())
                    <div class="text-red-600 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button type="submit"
                    class="w-full py-2 bg-gradient-to-r from-orange-400 to-blue-500 text-white font-semibold rounded-lg hover:opacity-90 transition">
                    Masuk
                </button>

                <p class="text-sm text-center mt-4">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar disini</a>
                </p>
            </form>
        </div>

        {{-- Bagian Kanan: Ilustrasi --}}
        <div class="hidden md:block w-1/2 bg-blue-500 relative">
            <img src="{{ asset('images/login-illustration.svg') }}" alt="Ilustrasi Login"
                class="absolute bottom-0 w-full h-auto">
        </div>
    </div>

</body>
</html>
