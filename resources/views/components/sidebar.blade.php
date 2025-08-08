<div class="w-64 bg-gradient-to-br from-blue-700 to-blue-900 text-white min-h-screen flex flex-col p-6 shadow-lg">
    <div class="mb-10 flex flex-col items-center">
        <div onclick="document.getElementById('logoutBtn').classList.toggle('hidden')"
            class="w-20 h-20 bg-white text-blue-700 flex items-center justify-center text-4xl rounded-full cursor-pointer shadow-lg transform transition-transform duration-300 hover:scale-110 mb-3">
            👤
        </div>
        <!-- <span class="text-lg font-semibold mb-1">Nama Pengguna</span> {{-- Ganti dengan nama pengguna dinamis --}}
        <span class="text-sm text-blue-200">Jabatan</span> {{-- Ganti dengan jabatan dinamis --}} -->
        <form method="POST" action="{{ route('logout') }}" id="logoutBtn" class="hidden mt-4">
            @csrf
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-5 py-2 rounded-lg transition-colors duration-200 shadow-md">
                Logout
            </button>
        </form>
    </div>

    <nav class="flex flex-col flex-grow">
        <a href="{{ route('home') }}"
            class="flex items-center p-3 rounded-lg text-lg font-medium hover:bg-blue-600 transition-colors duration-200 mb-3">
            <span class="mr-3 text-2xl">🏠</span> Home
        </a>
        <a href="{{ route('karyawan-tetap.index') }}" {{-- Diubah ke rute resource index --}}
            class="flex items-center p-3 rounded-lg text-lg font-medium hover:bg-blue-600 transition-colors duration-200 mb-3">
            <span class="mr-3 text-2xl">👨‍💼</span> Karyawan Tetap
        </a>
        <a href="{{ route('karyawan-kontrak.index') }}" {{-- Diubah ke rute resource index --}}
            class="flex items-center p-3 rounded-lg text-lg font-medium hover:bg-blue-600 transition-colors duration-200 mb-3">
            <span class="mr-3 text-2xl">🤝</span> Karyawan Kontrak
        </a>
        {{-- Anda bisa menambahkan link lain di sini --}}
    </nav>

    <!-- <div class="mt-auto text-center text-blue-300 text-sm">
        <p>&copy; 2025 Aplikasi Karyawan</p>
        <p>Versi 1.0</p>
    </div> -->
</div>
