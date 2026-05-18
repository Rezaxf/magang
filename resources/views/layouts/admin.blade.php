<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Manajemen Aset</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 overflow-hidden">

<div class="flex h-screen">

    {{-- ================= SIDEBAR DESKTOP ================= --}}
    <aside class="hidden md:flex w-64 bg-gradient-to-b from-blue-700 to-blue-600 text-white flex-col shadow-lg">

        {{-- LOGO --}}
        <div class="p-6 border-b border-blue-500">
            <h1 class="text-xl font-bold">Sistem Aset</h1>
            <p class="text-sm opacity-80">Kominfo</p>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 p-4 space-y-2 text-sm">

            <a href="/dashboard"
                class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                {{ request()->is('dashboard') ? 'bg-white text-blue-700 font-semibold shadow' : 'hover:bg-blue-500' }}">
                📊 Dashboard
            </a>

            <a href="/aset"
                class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                {{ request()->is('aset*') ? 'bg-white text-blue-700 font-semibold shadow' : 'hover:bg-blue-500' }}">
                📦 Aset
            </a>

            <a href="/riwayat-aset"
                class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                {{ request()->is('riwayat-aset*') ? 'bg-white text-blue-700 font-semibold shadow' : 'hover:bg-blue-500' }}">
                🕘 Riwayat Aset
            </a>

            <a href="/laporan"
                class="flex items-center gap-3 px-4 py-2 rounded-lg transition
                {{ request()->is('laporan*') ? 'bg-white text-blue-700 font-semibold shadow' : 'hover:bg-blue-500' }}">
                📄 Laporan
            </a>

        </nav>

        {{-- LOGOUT --}}
        <div class="p-4 border-t border-blue-500">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-red-500 hover:bg-red-600 py-2 rounded-lg font-semibold">
                    Logout
                </button>
            </form>
        </div>

    </aside>


    {{-- ================= MAIN ================= --}}
    <div class="flex-1 flex flex-col">

        {{-- ================= TOPBAR ================= --}}
        <header class="bg-white shadow-sm px-4 md:px-6 py-4 flex justify-between items-center">

            <div class="flex items-center gap-3">

                {{-- ☰ MOBILE BUTTON --}}
                <button onclick="toggleSidebar()" class="md:hidden text-xl text-gray-700">
                    ☰
                </button>

                {{-- 🔥 DINAMIS HEADER --}}
                <h2 class="text-base md:text-lg font-semibold text-gray-700">
                    {{ $header ?? 'Sistem Manajemen Aset' }}
                </h2>

            </div>

            <div class="flex items-center gap-3 md:gap-4">

                <span class="hidden sm:block text-gray-600 text-sm">
                    {{ Auth::user()->name ?? 'User' }}
                </span>

                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}"
                     class="w-8 h-8 rounded-full">

            </div>

        </header>


        {{-- ================= CONTENT ================= --}}
        <main class="flex-1 overflow-y-auto p-4 md:p-6">
            {{ $slot }}
        </main>

    </div>

</div>


{{-- ================= SIDEBAR MOBILE ================= --}}
<div id="mobileSidebar"
     onclick="toggleSidebar()"
     class="fixed inset-0 z-50 bg-black bg-opacity-40 hidden">

    <div class="w-64 bg-blue-600 h-full p-4 text-white"
         onclick="event.stopPropagation()">

        <button onclick="toggleSidebar()" class="mb-4 text-lg">
            ✕
        </button>

        <nav class="space-y-2 text-sm">

            <a href="/dashboard"
               class="block px-4 py-2 rounded
               {{ request()->is('dashboard') ? 'bg-white text-blue-700 font-semibold' : 'hover:bg-blue-500' }}">
               Dashboard
            </a>

            <a href="/aset"
               class="block px-4 py-2 rounded
               {{ request()->is('aset*') ? 'bg-white text-blue-700 font-semibold' : 'hover:bg-blue-500' }}">
               Aset
            </a>

            <a href="/riwayat-aset"
               class="block px-4 py-2 rounded
               {{ request()->is('riwayat-aset*') ? 'bg-white text-blue-700 font-semibold' : 'hover:bg-blue-500' }}">
               Riwayat
            </a>

            <a href="/laporan"
               class="block px-4 py-2 rounded
               {{ request()->is('laporan*') ? 'bg-white text-blue-700 font-semibold' : 'hover:bg-blue-500' }}">
               Laporan
            </a>

        </nav>

    </div>
</div>


{{-- ================= SCRIPT ================= --}}
<script>
function toggleSidebar() {
    document.getElementById('mobileSidebar').classList.toggle('hidden');
}
</script>

</body>
</html>