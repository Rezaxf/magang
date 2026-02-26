<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-extrabold text-gray-800">
                        Data Aset
                    </h3>

                    <a href="{{ route('aset.create') }}"
                        class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold text-sm shadow">
                        + Tambah Aset
                    </a>
                </div>

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-100 bg-gray-50">
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600">Nama Barang</th>
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600">Kode Barang</th>
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600">Merk/Tipe</th>
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600">Harga</th>
                                <th class="py-3 px-4 text-sm font-semibold text-gray-600 text-center">Aksi</th>
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
                                        {{ $aset->merk_tipe }}
                                    </td>

                                    <td class="py-4 px-4 text-sm text-gray-900 font-semibold">
                                        Rp {{ number_format($aset->harga, 0, ',', '.') }}
                                    </td>

                                    <td class="py-4 px-4 text-sm text-center">
                                        <div class="flex justify-center gap-2">

                                            {{-- EDIT BUTTON (HIJAU TERANG) --}}
                                            <a href="{{ route('aset.edit', $aset->id) }}"
                                                class="px-3 py-1 bg-green-600 text-white rounded-lg text-xs font-bold hover:bg-green-700 transition shadow">
                                                ✏ Edit
                                            </a>

                                            {{-- DELETE BUTTON --}}
                                            <form action="{{ route('aset.destroy', $aset->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition shadow">
                                                    Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="py-10 text-center text-gray-500 italic">
                                        Belum ada data aset.
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
</x-app-layout>