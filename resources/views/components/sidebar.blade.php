<div class="w-60 bg-blue-200 text-white flex flex-col items-center p-10">
    <div class="mb-6 flex flex-col items-center">
    <div onclick="document.getElementById('logoutBtn').classList.toggle('hidden')"
        class="w-16 h-16 bg-white text-blue-500 flex items-center justify-center text-3xl rounded-full cursor-pointer shadow">
        👤
    </div>
    <form method="POST" action="{{ route('logout') }}" id="logoutBtn" class="hidden mt-2">
        @csrf
        <button type="submit" class="bg-white text-blue-500 px-4 py-1 rounded">Logout</button>
    </form>
</div>
    <a href="{{ route('home') }}" class="mb-4 text-xl">🏠 Home</a>
    <a href="{{ route('tambah') }}" class="text-xl">➕ Tambah</a>
</div>
