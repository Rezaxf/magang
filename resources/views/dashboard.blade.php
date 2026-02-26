<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Ringkasan Aset Kominfo') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ================= IMPORT SECTION ================= --}}
            <div class="bg-white shadow-sm rounded-xl p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Import Data Aset</h3>

                <form action="{{ route('aset.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
                    @csrf
                    <input type="file" name="file" accept=".csv" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold text-sm">
                        UPLOAD CSV
                    </button>
                </form>

                @if(session('success'))
                    <p class="mt-3 text-sm text-green-600 font-semibold">
                        {{ session('success') }}
                    </p>
                @endif
            </div>


            {{-- ================= SUMMARY CARDS ================= --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-blue-600">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Aset</div>
                    <div class="mt-1 text-3xl font-bold text-gray-900">
                        {{ $asets->total() }}
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Nilai Aset</div>
                    <div class="mt-1 text-xl font-bold text-gray-900">
                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-yellow-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Halaman Saat Ini</div>
                    <div class="mt-1 text-lg font-bold text-gray-900">
                        {{ $asets->currentPage() }} dari {{ $asets->lastPage() }}
                    </div>
                </div>

            </div>


            {{-- ================= TABLE SECTION ================= --}}
            <div class="bg-white shadow-sm rounded-xl">
                <div class="p-6 text-gray-900">

                    {{-- HEADER + FILTER --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

                        <h3 class="text-lg font-bold text-gray-700">
                            Daftar Inventaris (Data Asli Kominfo)
                        </h3>

                        <form action="{{ route('dashboard') }}" method="GET"
                            class="flex flex-col md:flex-row gap-3 w-full md:w-auto">

                            {{-- SEARCH TEXT --}}
                            <input type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari nama, kode, atau merk..."
                                class="px-4 py-2 border border-gray-300 rounded-xl text-sm w-full md:w-60">

                            {{-- DROPDOWN MERK --}}
                            <select name="merk"
                                class="px-4 py-2 border border-gray-300 rounded-xl text-sm w-full md:w-60">

                                <option value="">-- Semua Merk/Tipe --</option>

                                @foreach($listMerk as $m)
                                    <option value="{{ $m }}"
                                        {{ request('merk') == $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach

                            </select>

                            {{-- BUTTON FILTER --}}
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                                Filter
                            </button>

                            {{-- RESET --}}
                            @if(request('search') || request('merk'))
                                <a href="{{ route('dashboard') }}"
                                   class="px-4 py-2 bg-gray-300 rounded-xl text-sm font-semibold hover:bg-gray-400 transition">
                                    Reset
                                </a>
                            @endif

                        </form>
                    </div>


                    {{-- TABLE --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b-2 border-gray-100 bg-gray-50">
                                    <th class="py-3 px-4 text-sm font-semibold text-gray-600">Nama Barang</th>
                                    <th class="py-3 px-4 text-sm font-semibold text-gray-600">Kode Barang</th>
                                    <th class="py-3 px-4 text-sm font-semibold text-gray-600">NUP</th>
                                    <th class="py-3 px-4 text-sm font-semibold text-gray-600">Merk/Tipe</th>
                                    <th class="py-3 px-4 text-sm font-semibold text-gray-600">Harga</th>
                                    <th class="py-3 px-4 text-sm font-semibold text-gray-600">Perolehan</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-50">
                                @forelse ($asets as $aset)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-4 px-4 text-sm text-gray-800 font-medium">
                                            {{ $aset->nama_barang }}
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-600 font-mono">
                                            {{ $aset->kode_barang }}
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-600">
                                            {{ $aset->nup }}
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-600">
                                            {{ $aset->merk_tipe }}
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-900 font-semibold">
                                            Rp {{ number_format($aset->harga, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-4 text-sm">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase">
                                                {{ $aset->cara_perolehan }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-gray-500 italic">
                                            @if(request('search') || request('merk'))
                                                Data tidak ditemukan sesuai filter.
                                            @else
                                                Belum ada data.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-6">
                        {{ $asets->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>