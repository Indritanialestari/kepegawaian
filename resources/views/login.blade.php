<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Gaya kustom untuk latar belakang yang lebih lembut */
        body {
            /* Contoh 1: Gradien biru muda ke abu-abu muda */
            background: linear-gradient(to right, #e0f2fe, #e2e8f0); /* bg-blue-100 ke bg-gray-200 */

            /* Contoh 2 (Alternatif, jika Contoh 1 kurang cocok): Gradien peach muda ke lilac muda */
            /* background: linear-gradient(to right, #ffe4e1, #e6e6fa); */ /* Warna soft pink ke soft purple */

            /* Contoh 3 (Alternatif): Gradien mint ke light blue */
            /* background: linear-gradient(to right, #d4f8e8, #cceeff); */
        }
    </style>
</head>
<body class="flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-xl w-96 max-w-sm transform transition-all duration-300 hover:scale-105">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-900">Selamat Datang Kembali</h2>
            <p class="mt-2 text-sm text-gray-600">Masuk ke akun Anda</p>
        </div>

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Pengguna</label>
                <input type="text" name="name" id="name" placeholder="Masukkan nama pengguna Anda" value="{{ old('name') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                              @error('name') border-red-500 @enderror" required autofocus>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password Anda"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                              @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex items-center justify-end mb-6">
                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Lupa Password?</a>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Login
                </button>
            </div>
        </form>
    </div>
</body>
</html>