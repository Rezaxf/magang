<nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- LEFT: LOGO + TITLE --}}
            <div class="flex items-center gap-4">

                {{-- Logo / Nama --}}
                <div class="text-lg font-bold text-blue-600">
                    Sistem Aset
                </div>

                {{-- Divider --}}
                <div class="hidden md:block h-6 w-px bg-gray-300"></div>

                {{-- Judul Halaman --}}
                <div class="hidden md:block text-gray-700 font-semibold">
                    Dashboard Manajemen Aset Kominfo
                </div>

            </div>

            {{-- RIGHT: USER --}}
            <div class="flex items-center gap-4">

                {{-- User Dropdown --}}
                <div class="relative">
                    <button onclick="toggleDropdown()"
                        class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition">

                        <span>{{ Auth::user()->name ?? 'User' }}</span>

                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div id="dropdownUser"
                        class="hidden absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-md z-50">

                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
</nav>

{{-- SCRIPT DROPDOWN --}}
<script>
function toggleDropdown() {
    const dropdown = document.getElementById('dropdownUser');
    dropdown.classList.toggle('hidden');
}

// close jika klik luar
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('dropdownUser');
    if (!e.target.closest('button')) {
        dropdown.classList.add('hidden');
    }
});
</script>